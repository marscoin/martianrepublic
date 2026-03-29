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
import uuid
from datetime import datetime

# Load environment variables
load_dotenv("../.env")
BALLOT_SERVER_HOST = os.getenv('BALLOT_SERVER_HOST')
BALLOT_SERVER_PORT = os.getenv('BALLOT_SERVER_PORT')
SERVER_SSL_CERT = os.getenv('SSL_CERT')
SERVER_SSL_KEY = os.getenv('SSL_KEY')

# Configure logging
logging.basicConfig(level=logging.DEBUG)
ssl_context = ssl.SSLContext(ssl.PROTOCOL_TLS_SERVER)

# Load SSL certificate and key
ssl_cert = SERVER_SSL_CERT
ssl_key = SERVER_SSL_KEY
ssl_context.load_cert_chain(ssl_cert, keyfile=ssl_key)

# Global state and data structures
STATE = {"value": 0}
USERS = {}
SHUFFLE = {}
SIGNATURES = {}
PEER_NUM = 0
CURRENT_SHUFFLE_PEER = 0
response = {}
server = CoinShuffleServer()
max_voters_required = 3

# Client and room tracking
clients = {}  # : {client_id: {websocket, name, room, address, last_seen}}
rooms = {}  # : {room: {websocket: name}}
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
        raise Exception("BallotShuffle already Started")
    # Send INITIATE_SHUFFLE to all participants
    for i in range(len(response['order']) - 1, -1, -1):
        ek = response['order'][i]
        peer_addr = response['peers'][ek]  # This is now the Marscoin address
        logging.info(f"Sending INITIATE_SHUFFLE to peer {peer_addr}")
        for client, client_name in room.items():
            if client_name == peer_addr:
                await client.send('INITIATE_SHUFFLE_' + json.dumps(encoded_response))
                break

    # Perform shuffle starting with peer 0
    data = {}
    sources = {}
    CURRENT_SHUFFLE_PEER = 0
    ek = response['order'][CURRENT_SHUFFLE_PEER]
    peer_addr = response['peers'][ek]  # Marscoin address
    logging.info(f"Starting shuffle with peer 0: {peer_addr}")
    for client, client_name in room.items():
        if client_name == peer_addr:
            await client.send('PERFORM_SHUFFLE#' + json.dumps(data) + "#" + json.dumps(sources))
            break
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

async def register(websocket, address):
    USERS[websocket] = {"address": address, "last_seen": datetime.now().strftime("%Y-%m-%d %H:%M:%S")}
    await notify_users()

async def unregister(websocket):
    if websocket in USERS:
        del USERS[websocket]
    await notify_users()

async def restore_client_state(websocket, client_id):
    if client_id in client_states:
        state = client_states[client_id]
        await websocket.send(json.dumps({"action": "restore", "state": state}))

async def save_client_state(client_id, state):
    client_states[client_id] = state

async def client_handler(websocket):
    client_id = None
    try:
        logging.debug(f'New voter: {websocket.remote_address}')
        name_prop = await websocket.recv()
        name, room = name_prop.split("_")
        address = name  # Assuming name is the public address

        logging.debug(f"Received name and room: {name}, {room}")

        # Check if this address is already connected
        existing_client = None
        for cid, info in clients.items():
            if info["address"] == address:
                existing_client = cid
                break

        if existing_client:
            logging.debug(f"Existing connection found for {address}, closing it.")
            old_ws = clients[existing_client]["websocket"]
            old_room = clients[existing_client].get("room")
            # Remove from rooms BEFORE closing - prevents old handler's finally from interfering
            if old_room and old_room in rooms and old_ws in rooms[old_room]:
                del rooms[old_room][old_ws]
            # Remove from clients BEFORE closing - the await old_ws.close() suspends us,
            # letting the old handler's finally block run. If it deletes the client first,
            # our del below would KeyError and crash the new handler before it registers.
            clients.pop(existing_client, None)
            try:
                await old_ws.close()
            except Exception:
                pass

        client_id = str(uuid.uuid4())
        clients[client_id] = {"websocket": websocket, "name": name, "room": room, "address": address, "last_seen": datetime.now().strftime("%Y-%m-%d %H:%M:%S")}

        if room not in rooms:
            rooms[room] = {}
        rooms[room][websocket] = name

        logging.debug(f"Martian: {name} joined for {room}")
        await websocket.send(f'Welcome to the Ballot issuing server. It will coordinate your request to vote by issuing a ballot that cannot be traced back to you but still audited by the community as originating from the voter registry thus securing cryptographically secured free and fair elections/votes, {name}')
        await websocket.send(f'There are {len(clients) - 1} other Martian citizens connected awaiting a ballot issuance: {list(info["address"] for info in clients.values())}')
        await restore_client_state(websocket, client_id)
        await websocket.send("JOINED_ACK")

        for client, _ in rooms[room].items():
            await client.send(f'{name} has joined the Ballot issuance procedures for proposal {room}')
            await client.send(f"...waiting for {max_voters_required - len(rooms[room])} more voters to join before ballot shuffle commences...")
            await client.send("Please wait...")

        while True:
            try:
                message = await websocket.recv()
                logging.debug(f"Received message: {message}")

                if not message:
                    break

                if "SUBMIT_KEY" in message:
                    key = message.split("#")[1]
                    # Use Marscoin address as peer identifier (not IP:port which breaks behind proxy)
                    server.submit_public_key(key, name)
                    logging.info(f"Key submitted by {name} (peer ID: {name})")
                    if len(rooms[room]) >= max_voters_required:
                        await ballotserver_start(rooms[room])

                if "PERFORM_SHUFFLE_ACK" in message:
                    peer_index = int(message.split("#")[1])
                    SHUFFLE[peer_index] = message.split("#")[2]
                    PEER_NUM = len(rooms[room])
                    if len(SHUFFLE) < PEER_NUM:
                        obj = SHUFFLE[peer_index]
                        obj = ast.literal_eval(obj)
                        data = obj['data']
                        sources = obj['sources']
                        peer_index += 1
                        ek = response['order'][peer_index]
                        peer_addr = response['peers'][ek]  # Marscoin address
                        logging.info(f"Forwarding shuffle to peer {peer_index}: {peer_addr}")
                        for client, client_name in rooms[room].items():
                            if client_name == peer_addr:
                                await client.send(f'PERFORM_SHUFFLE#{json.dumps(data)}#{json.dumps(sources)}')
                                break
                    else:
                        logging.debug("Now sign the transaction?")

                if "COLLECT_SIGNATURES" in message:
                    raw_tx = message.split("#")[1]
                    for client, _ in rooms[room].items():
                        await client.send(f'SIGN_TX#{raw_tx}')

                if "SIGN_TX_COMPLETE" in message:
                    peer_index = int(message.split("#")[1])
                    SIGNATURES[peer_index] = message.split("#")[2]
                    PEER_NUM = len(rooms[room])
                    if len(SIGNATURES) == PEER_NUM:
                        for client, _ in rooms[room].items():
                            await client.send(f'COMBINE_AND_BROADCAST#{json.dumps(SIGNATURES)}')
            except (ConnectionClosedError, ConnectionClosedOK) as e:
                logging.error(f"Connection closed with error from client: {websocket.remote_address}")
                break
            except WebSocketException as e:
                logging.error(f"WebSocket error: {e}")
                break
            except Exception as e:
                logging.error(f"Unexpected error: {e}")
                break
    except Exception as e:
        logging.error(f"Error during client handler setup: {e}")
    finally:
        if client_id:
            # Clean up client
            if client_id in clients:
                del clients[client_id]
            # Ensure the client's websocket is removed from rooms
            if room in rooms and websocket in rooms[room]:
                del rooms[room][websocket]
            await unregister(websocket)
        logging.debug(f"Final client state: {clients}")
        logging.debug(f"Final room state: {rooms}")


async def main():
    # SSL server on all interfaces (for direct ballot.martianrepublic.org connections)
    server_ssl = await websockets.serve(client_handler, str(BALLOT_SERVER_HOST), BALLOT_SERVER_PORT, ssl=ssl_context)
    # Plain server on localhost (for Apache reverse proxy via /wss/ballot)
    server_plain = await websockets.serve(client_handler, "127.0.0.1", 3679)
    logging.info(f"Plain WebSocket also listening on 127.0.0.1:3679 (for Apache proxy)")
    await asyncio.gather(server_ssl.wait_closed(), server_plain.wait_closed())

if __name__ == "__main__":
    loop = asyncio.new_event_loop()
    asyncio.set_event_loop(loop)

    try:
        loop.run_until_complete(main())
    except KeyboardInterrupt:
        pass
    finally:
        loop.close()
