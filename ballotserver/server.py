#!/usr/bin/env python3
import asyncio
import json
import logging
import websockets
import ssl
import os
import ast
from dotenv import load_dotenv
from ballot_shuffle import CoinShuffleServer
import hashlib

load_dotenv("../.env")
BALLOT_SERVER_HOST = os.getenv('BALLOT_SERVER_HOST')
BALLOT_SERVER_PORT = os.getenv('BALLOT_SERVER_PORT')
SERVER_SSL_CERT = os.getenv('SSL_CERT')
SERVER_SSL_KEY = os.getenv('SSL_KEY')

logging.basicConfig()
ssl_context = ssl.SSLContext(ssl.PROTOCOL_TLS_SERVER)

# Generate with Lets Encrypt, copied to this location, chown to current user and 400 permissions
ssl_cert = SERVER_SSL_CERT
ssl_key =  SERVER_SSL_KEY

ssl_context.load_cert_chain(ssl_cert, keyfile=ssl_key)

STATE = {"value": 0}
USERS = set()
SHUFFLE = {}
SIGNATURES = {}
PEER_NUM = 0
CURRENT_SHUFFLE_PEER = 0
response = {}
server = CoinShuffleServer()
max_voters_required = 3

print("========================================================================")
print("")
print("                 MARTIAN REPUBLIC - BALLOT SHUFFLE SERVER")
print("")
print("========================================================================")
print("")
print("MAX_VOTERS_REQUIRED: " + str(max_voters_required))
print("BALLOT_SERVER_HOST: " + str(BALLOT_SERVER_HOST))
print("BALLOT_SERVER_PORT: " + str(BALLOT_SERVER_PORT))
print("")
async def ballotserver_start(room):
        global response
        response, dostart = server.start()
        encoded_response = {'order' : json.dumps(response['order']),
                            'peers' : json.dumps(response['peers'])}
        if not dostart:
            abort(555, message="BallotShuffle already Started")
        for i in range(len(response['order'])-1,-1,-1):
            ek = response['order'][i]
            addr = response['peers'][ek]
            for client, _ in room.items():
                client_addr = str(client.remote_address[0])+":"+str(client.remote_address[1])
                if client_addr == addr:
                    await client.send('INITIATE_SHUFFLE_' + json.dumps(encoded_response))

        #assume all initializations have taken place
        #now perform shuffle in reverse order. kick off with first client in list
        data = {}
        sources = {}
        CURRENT_SHUFFLE_PEER = 0
        ek = response['order'][CURRENT_SHUFFLE_PEER]
        addr = response['peers'][ek]
        for client, _ in room.items():
            client_addr = str(client.remote_address[0]) + ":" + str(client.remote_address[1])
            if client_addr == addr:
                await client.send('PERFORM_SHUFFLE#' + json.dumps(data) + "#" + json.dumps(sources))
        return

async def concatenateSignatures(signatures):
    return ""

async def broadcastFinalShuffleTx(final_tx):
    return ""


def state_event():
    return json.dumps({"type": "state", **STATE})


def users_event():
    return json.dumps({"type": "users", "count": len(USERS)})


async def notify_state():
    if USERS:  # asyncio.wait doesn't accept an empty list
        message = state_event()
        await asyncio.wait([user.send(message) for user in USERS])


async def notify_users():
    if USERS:  # asyncio.wait doesn't accept an empty list
        message = users_event()
        await asyncio.wait([user.send(message) for user in USERS])


async def register(websocket):
    USERS.add(websocket)
    await notify_users()


async def unregister(websocket):
    USERS.remove(websocket)
    await notify_users()


async def counter(websocket, path):
    # register(websocket) sends user_event() to websocket
    await register(websocket)
    try:
        await websocket.send(state_event())
        async for message in websocket:
            data = json.loads(message)
            if data["action"] == "minus":
                STATE["value"] -= 1
                await notify_state()
            elif data["action"] == "plus":
                STATE["value"] += 1
                await notify_state()
            else:
                logging.error("unsupported event: {}", data)
    finally:
        await unregister(websocket)

# The set of clients connected to this server. It is used to distribute
# messages.
clients = {} #: {websocket: name}
rooms = {}  #: {websocket: proposalname}
entity = {}

async def client_handler(websocket, path):
    print('New voter: '+ str(websocket.remote_address[0])+":"+str(websocket.remote_address[1]))
    current = len(clients.items()) + 1
    print(' ({} existing clients)'.format(current))
    print("...waiting for " + str(max_voters_required - current) + " more voters to request ballot before shuffle commences...")
    # The first line from the client is the name
    name_prop = await websocket.recv()
    name = name_prop.split("_")[0]
    room = name_prop.split("_")[1]
    print("Martian: " + str(name) + " joined for " + str(room))
    await websocket.send('Welcome to the Ballot issuing server. It will coordinate your request to vote by issuing a ballot that cannot be traced back to you but still audited by the community as originating from the voter registry thus securing cryptographically secured free and fair elections/votes, {}'.format(name))
    await websocket.send('There are {} other Martian citizens connected awaiting a ballot issuance: {}'.format(len(clients), list(clients.values())))
    entity[websocket] = name
    rooms[room] = entity
    await websocket.send("JOINED_ACK")

    #let everyone else in the room know
    for client, _ in rooms[room].items():
        await client.send(name + ' has joined the Ballot issuance procedures for proposal ' + str(room))
        await client.send("...waiting for " + str(max_voters_required - current) + " more voters to join before ballot shuffle commences...")
        await client.send("Please wait...")

    # Handle messages from this client
    while True:
        message = await websocket.recv()
        if message is None:
            their_name = clients[websocket]
            del clients[websocket]
            print('Client closed connection', websocket)
            for client, _ in clients.items():
                await client.send(their_name + ' has left, restart shuffle...')
            break

        if "SUBMIT_KEY" in message:
            #requests.post(addr + '/coinshuffle/submitkey', data={'public_key': self.ek, 'address': self.addr})
            key = message.split("#")[1]
            server.submit_public_key(key, str(websocket.remote_address[0])+":"+str(websocket.remote_address[1]))
            # Do we have enough ballot requests pending for a given proposal "polling station" (room)
            if len(rooms[room].items()) >= max_voters_required:
                # Let the most recent client that joined kick the shuffle process off
                await ballotserver_start(rooms[room])

        if "PERFORM_SHUFFLE_ACK" in message:
            peer_index = int(message.split("#")[1])
            SHUFFLE[peer_index] = message.split("#")[2]

            PEER_NUM = len(rooms[room].items())
            print(SHUFFLE)
            print(PEER_NUM)
            print(len(SHUFFLE))
            if len(SHUFFLE) <= PEER_NUM:
                obj = SHUFFLE[peer_index]
                print(obj)
                obj = ast.literal_eval(obj)
                print(obj['data'])
                data = obj['data']
                sources = obj['sources']
                peer_index = peer_index + 1
                ek = response['order'][peer_index]
                addr = response['peers'][ek]
                for client, _ in rooms[room].items():
                    client_addr = str(client.remote_address[0]) + ":" + str(client.remote_address[1])
                    if client_addr == addr:
                        await client.send('PERFORM_SHUFFLE#' + json.dumps(data) + "#" + json.dumps(sources))

            else:
                print("Now sign the transaction?")

        if "COLLECT_SIGNATURES" in message:
            raw_tx = message.split("#")[1]
            for client, _ in rooms[room].items():
                await client.send('SIGN_TX#' + raw_tx)

        if "SIGN_TX_COMPLETE" in message:
            peer_index = int(message.split("#")[1])
            SIGNATURES[peer_index] = message.split("#")[2]
            print("Peer: " + str(peer_index))
            print(hashlib.md5(SIGNATURES[peer_index].encode()).hexdigest())

            PEER_NUM = len(rooms[room].items())
            # print(SIGNATURES)
            # print(PEER_NUM)
            print(len(SIGNATURES))
            if len(SIGNATURES) == PEER_NUM:
                #Send to all clients for combining and broadcasting...
                for client, _ in rooms[room].items():
                    await client.send('COMBINE_AND_BROADCAST#' + json.dumps(SIGNATURES))

        # Send message to all clients
        #for client, _ in clients.items():
        #    await client.send('{}: {}'.format(name, message))

start_server = websockets.serve(client_handler, str(BALLOT_SERVER_HOST), BALLOT_SERVER_PORT, ssl=ssl_context)

asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()


#https://github.com/bitcoinjs/bitcoinjs-lib/blob/533d6c2e6d0aa4111f7948b1c12003cf6ef83137/test/integration/transactions.spec.ts#L19
