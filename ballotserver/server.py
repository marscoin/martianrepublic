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
max_voters_required = int(os.getenv('BALLOT_MIN_VOTERS', '3'))

# The more citizens share a round, the less anyone can infer about who cast
# which ballot. Once the minimum is present the round stays open a little
# longer so that others can join, and starts at once if it fills up.
GATHER_SECONDS = float(os.getenv('BALLOT_GATHER_SECONDS', '90'))
MAX_VOTERS = int(os.getenv('BALLOT_MAX_VOTERS', '12'))

# Shuffle state is kept per proposal room and cleared after every round, so a
# finished or abandoned round can never leak keys, shuffle steps or signatures
# into the next one.
ROUNDS = {}  # : {room: {server, response, shuffle, signatures, active, waiting}}


def round_state(room_name):
    if room_name not in ROUNDS:
        ROUNDS[room_name] = {
            "server": CoinShuffleServer(),
            "response": None,
            "shuffle": {},
            "signatures": {},
            "active": False,
            "waiting": set(),
            "timer": None,
        }
    return ROUNDS[room_name]


def cancel_gathering(state):
    timer = state.get("timer")
    state["timer"] = None
    if timer and timer is not asyncio.current_task():
        timer.cancel()


async def tell_room(room_name, message, only=None):
    for client, client_name in list(rooms.get(room_name, {}).items()):
        if only is None or client_name in only:
            try:
                await client.send(message)
            except Exception:
                pass


def ready_voters(room_name):
    """Voters who have offered a key and are still connected."""
    state = round_state(room_name)
    present = set(rooms.get(room_name, {}).values())
    for absent in [p for p in state["server"].peers if p not in present]:
        state["server"].remove(absent)
    return list(state["server"].peers)


async def start_after_gathering(room_name):
    """Waits for more voters, then starts the round with everyone ready."""
    state = round_state(room_name)
    try:
        await tell_room(room_name, f"GATHERING#{int(GATHER_SECONDS)}")
        await asyncio.sleep(GATHER_SECONDS)
        state["timer"] = None
        if not state["active"] and len(ready_voters(room_name)) >= max_voters_required:
            await ballotserver_start(room_name)
    except asyncio.CancelledError:
        pass
    except Exception as e:
        logging.error(f"Could not start the round for {room_name}: {e}")
        state["timer"] = None


def round_participants(state):
    if not state["response"]:
        return []
    return list(state["response"]["peers"].values())


async def end_round(room_name, invite):
    """Clears a room's shuffle state. Voters in `invite` are asked to submit a
    key again (the same JOINED_ACK they answer on arrival), which forms the
    next round as soon as enough of them are present."""
    state = round_state(room_name)
    cancel_gathering(state)
    state["server"].reset()
    state["response"] = None
    state["shuffle"] = {}
    state["signatures"] = {}
    state["active"] = False
    state["waiting"] = set()
    for client, client_name in list(rooms.get(room_name, {}).items()):
        if client_name in invite:
            try:
                await client.send("JOINED_ACK")
            except Exception:
                pass

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

async def ballotserver_start(room_name):
    room = rooms[room_name]
    state = round_state(room_name)
    response, dostart = state["server"].start()
    encoded_response = {'order': json.dumps(response['order']),
                        'peers': json.dumps(response['peers'])}
    if not dostart:
        raise Exception("BallotShuffle already Started")
    state["response"] = response
    state["shuffle"] = {}
    state["signatures"] = {}
    state["active"] = True
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
    ek = response['order'][0]
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
            await client.send(f"...waiting for {max(0, max_voters_required - len(rooms[room]))} more voters to join before ballot shuffle commences...")
            await client.send("Please wait...")

        while True:
            try:
                message = await websocket.recv()
                logging.debug(f"Received message: {message}")

                if not message:
                    break

                state = round_state(room)

                if "SUBMIT_KEY" in message:
                    key = message.split("#")[1]
                    if state["active"]:
                        # A round is under way. This voter is invited to the
                        # next one as soon as it finishes or is abandoned.
                        state["waiting"].add(name)
                        logging.info(f"{name} will join the next round for {room}")
                        continue
                    # Use Marscoin address as peer identifier (not IP:port which breaks behind proxy)
                    state["server"].submit_public_key(key, name)
                    logging.info(f"Key submitted by {name} (peer ID: {name})")
                    # Only voters who are still connected take part.
                    ready = len(ready_voters(room))
                    await tell_room(room, f"VOTERS_PRESENT#{ready}")
                    if ready >= MAX_VOTERS:
                        cancel_gathering(state)
                        await ballotserver_start(room)
                    elif ready >= max_voters_required and state["timer"] is None:
                        state["timer"] = asyncio.create_task(start_after_gathering(room))

                if "PERFORM_SHUFFLE_ACK" in message:
                    if not state["active"] or name not in round_participants(state):
                        continue
                    response = state["response"]
                    SHUFFLE = state["shuffle"]
                    peer_index = int(message.split("#")[1])
                    SHUFFLE[peer_index] = message.split("#")[2]
                    PEER_NUM = len(response['order'])
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
                    if not state["active"] or name not in round_participants(state):
                        continue
                    participants = round_participants(state)
                    raw_tx = message.split("#")[1]
                    for client, client_name in rooms[room].items():
                        if client_name in participants:
                            await client.send(f'SIGN_TX#{raw_tx}')

                if "SIGN_TX_COMPLETE" in message:
                    if not state["active"] or name not in round_participants(state):
                        continue
                    participants = round_participants(state)
                    SIGNATURES = state["signatures"]
                    peer_index = int(message.split("#")[1])
                    SIGNATURES[peer_index] = message.split("#")[2]
                    if len(SIGNATURES) == len(participants):
                        for client, client_name in rooms[room].items():
                            if client_name in participants:
                                await client.send(f'COMBINE_AND_BROADCAST#{json.dumps(SIGNATURES)}')
                        # Round complete: start clean, and invite anyone who
                        # arrived while it was running.
                        await end_round(room, set(state["waiting"]))
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
            # A voter who is no longer connected (and has not reconnected on
            # another socket) cannot take part in a shuffle.
            if room in rooms and name not in rooms[room].values():
                state = round_state(room)
                state["waiting"].discard(name)
                if state["active"] and name in round_participants(state):
                    # The round cannot complete without them. Start over with
                    # everyone who is still here.
                    logging.info(f"{name} left during the shuffle for {room}; restarting the round")
                    await end_round(room, set(rooms[room].values()))
                else:
                    state["server"].remove(name)
                    ready = len(ready_voters(room))
                    await tell_room(room, f"VOTERS_PRESENT#{ready}")
                    if ready < max_voters_required:
                        # Too few are left to start; wait for others again.
                        cancel_gathering(state)
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
