#!/usr/bin/env python
import asyncio
import json
import logging
import websockets
import ssl
import os
from dotenv import load_dotenv
from ballot_shuffle import CoinShuffleServer

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

server = CoinShuffleServer()

async def ballotserver_start(room):
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
        #now perform shuffle in reverse order
        data = {}
        sources = {}
        for i in range(0, len(response['order']) - 1, 1):
            ek = response['order'][i]
            addr = response['peers'][ek]
            for client, _ in room.items():
                client_addr = str(client.remote_address[0]) + ":" + str(client.remote_address[1])
                if client_addr == addr:
                    await client.send('PERFORM_SHUFFLE_' + json.dumps(data) + "_" + json.dumps(sources))
                    while True:
                        if client_addr in SHUFFLE:
                            data = SHUFFLE[client_addr]
                            data = json.loads(ret.split(",")[0])
                            sources = json.loads(ret.split(",")[1])
        return

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
    print('New client', websocket)
    print(' ({} existing clients)'.format(len(clients)))

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

    # Handle messages from this client
    while True:
        message = await websocket.recv()
        if message is None:
            their_name = clients[websocket]
            del clients[websocket]
            print('Client closed connection', websocket)
            for client, _ in clients.items():
                await client.send(their_name + ' has left the chat')
            break

        if "SUBMIT_KEY" in message:
            #requests.post(addr + '/coinshuffle/submitkey', data={'public_key': self.ek, 'address': self.addr})
            key = message.split("#")[1]
            server.submit_public_key(key, str(websocket.remote_address[0])+":"+str(websocket.remote_address[1]))
            # Do we have enough ballot requests pending for a given proposal "polling station" (room)
            if len(rooms[room].items()) >= 3:
                # Let the most recent client that joined kick the shuffle process off
                await ballotserver_start(rooms[room])

        if "PERFORM_SHUFFLE_ACK" in message:
            SHUFFLE[str(websocket.remote_address[0])+":"+str(websocket.remote_address[1])] = message

        # Send message to all clients
        #for client, _ in clients.items():
        #    await client.send('{}: {}'.format(name, message))

start_server = websockets.serve(client_handler, str(BALLOT_SERVER_HOST), BALLOT_SERVER_PORT, ssl=ssl_context)

asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()
