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
from websockets.exceptions import ConnectionClosedError, ConnectionClosedOK, WebSocketException
import hashlib

# Load environment variables
load_dotenv("../.env")
BALLOT_SERVER_HOST = os.getenv('BALLOT_SERVER_HOST')
BALLOT_SERVER_PORT = os.getenv('BALLOT_SERVER_PORT')
SERVER_SSL_CERT = os.getenv('SSL_CERT')
SERVER_SSL_KEY = os.getenv('SSL_KEY')

# Configure logging
logging.basicConfig()
ssl_context = ssl.SSLContext(ssl.PROTOCOL_TLS_SERVER)

# Load SSL certificate and key
ssl_cert = SERVER_SSL_CERT
ssl_key = SERVER_SSL_KEY
ssl_context.load_cert_chain(ssl_cert, keyfile=ssl_key)

# Global state and data structures
STATE = {"value": 0}
USERS = set()
SHUFFLE = {}
SIGNATURES = {}
PEER_NUM = 0
CURRENT_SHUFFLE_PEER = 0
response = {}
server = CoinShuffleServer()
max_voters_required = 3

# Client and room tracking
clients = {}  # : {websocket: name}
rooms = {}  # : {websocket: proposalname}
entity = {}
client_states = {}  # : {client_id: {state}}

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
    encoded_response = {'order': json.dumps(response['order']),
                        'peers': json.dumps(response['peers'])}
    if not dostart:
        abort(555, message="BallotShuffle already Started")
    for i in range(len(response['order']) - 1, -1, -1):
        ek = response['order'][i]
        addr = response['peers'][ek]
        for client, _ in room.items():
            client_addr = str(client.remote_address[0]) + ":" + str(client.remote_address[1])
            if client_addr == addr:
                await client.send('INITIATE_SHUFFLE_' + json.dumps(encoded_response))

    # Perform shuffle in reverse order
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
    if USERS:
        message = state_event()
        await asyncio.wait([user.send(message) for user in USERS])

async def notify_users():
    if USERS:
        message = users_event()
        await asyncio.wait([user.send(message) for user in USERS])

async def register(websocket):
    USERS.add(websocket)
    await notify_users()

async def unregister(websocket):
    if websocket in USERS:
        USERS.remove(websocket)
    await notify_users()

async def restore_client_state(websocket, client_id):
    if client_id in client_states:
        state = client_states[client_id]
        await websocket.send(json.dumps({"action": "restore", "state": state}))

async def save_client_state(client_id, state):
    client_states[client_id] = state

async def counter(websocket, path):
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

async def client_handler(websocket, path):
    try:
        print('New voter: ' + str(websocket.remote_address[0]) + ":" + str(websocket.remote_address[1]))
        current = len(clients.items()) + 1
        print(' ({} existing clients)'.format(current))
        print("...waiting for " + str(max_voters_required - current) + " more voters to request ballot before shuffle commences...")
        name_prop = await websocket.recv()
        name = name_prop.split("_")[0]
        room = name_prop.split("_")[1]
        client_id = name + "_" + room

        print("Martian: " + str(name) + " joined for " + str(room))
        await websocket.send('Welcome to the Ballot issuing server. It will coordinate your request to vote by issuing a ballot that cannot be traced back to you but still audited by the community as originating from the voter registry thus securing cryptographically secured free and fair elections/votes, {}'.format(name))
        await websocket.send('There are {} other Martian citizens connected awaiting a ballot issuance: {}'.format(len(clients), list(clients.values())))
        entity[websocket] = name
        rooms[room] = entity

        # Restore client state if it exists
        await restore_client_state(websocket, client_id)

        await websocket.send("JOINED_ACK")

        for client, _ in rooms[room].items():
            await client.send(name + ' has joined the Ballot issuance procedures for proposal ' + str(room))
            await client.send("...waiting for " + str(max_voters_required - current) + " more voters to join before ballot shuffle commences...")
            await client.send("Please wait...")

        while True:
            try:
                message = await websocket.recv()
                if message is None:
                    their_name = clients[websocket]
                    del clients[websocket]
                    print('Client closed connection', websocket)
                    for client, _ in clients.items():
                        await client.send(their_name + ' has left, restart shuffle...')
                    break

                if "SUBMIT_KEY" in message:
                    key = message.split("#")[1]
                    server.submit_public_key(key, str(websocket.remote_address[0]) + ":" + str(websocket.remote_address[1]))
                    if len(rooms[room].items()) >= max_voters_required:
                        await ballotserver_start(rooms[room])

                if "PERFORM_SHUFFLE_ACK" in message:
                    peer_index = int(message.split("#")[1])
                    SHUFFLE[peer_index] = message.split("#")[2]

                    PEER_NUM = len(rooms[room].items())
                    if len(SHUFFLE) <= PEER_NUM:
                        obj = SHUFFLE[peer_index]
                        obj = ast.literal_eval(obj)
                        data = obj['data']
                        sources = obj['sources']
                        peer_index += 1
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
                    PEER_NUM = len(rooms[room].items())
                    if len(SIGNATURES) == PEER_NUM:
                        for client, _ in rooms[room].items():
                            await client.send('COMBINE_AND_BROADCAST#' + json.dumps(SIGNATURES))

                # Save client state after processing each message
                await save_client_state(client_id, {"latest_message": message})

            except ConnectionClosedError:
                print(f"Connection closed with error from client: {websocket.remote_address}")
                break
            except ConnectionClosedOK:
                print(f"Connection closed gracefully by client: {websocket.remote_address}")
                break
            except WebSocketException as e:
                print(f"WebSocket error: {e}")
                break
            except Exception as e:
                print(f"Unexpected error: {e}")
                break
    except Exception as e:
        print(f"Error during client handler setup: {e}")
    finally:
        await unregister(websocket)

start_server = websockets.serve(client_handler, str(BALLOT_SERVER_HOST), BALLOT_SERVER_PORT, ssl=ssl_context)

asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()
