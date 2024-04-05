#!/usr/bin/python
"""
Martian Republic Blockchain Transaction Analyzer

This script is designed to automatically process blockchain transactions specifically for the Martian Republic project. It identifies and caches OP_RETURN codes and related transaction data into a database for further analysis and application use.

The script operates by interfacing with the Marscoin blockchain through CLI commands, parsing transaction data, and performing several key operations as outlined below:

1. Retrieves the latest block hash and count using the Marscoin CLI's `getblockchaininfo` command. This serves as the starting point for transaction analysis.

2. For the latest block, it fetches all associated transactions by executing the `getblock <blockhash>` command, where `<blockhash>` is the hash of the latest or specified block.

3. For each transaction obtained from the block, it fetches the raw transaction data using `getrawtransaction <txid>`, where `<txid>` is the transaction ID.

4. The raw transaction data is then decoded using `decoderawtransaction <rawtx>`, which provides a detailed JSON representation of the transaction, including all its inputs (vin) and outputs (vout).

5. The script specifically looks for transaction outputs of the "nulldata" type, which indicate the presence of OP_RETURN data. The OP_RETURN data typically contains arbitrary data embedded by various applications for different purposes, such as proving ownership of a document without revealing the information itself.

6. Upon identifying an OP_RETURN output, the script extracts and decodes the associated data for caching and further analysis. This data often represents significant actions or statements on the blockchain, serving various use cases from simple messages to complex smart contract operations.

The script utilizes a robust error handling and reconnection strategy for database interactions to ensure resilience against transient network or database server issues. It employs parameterized SQL queries to prevent SQL injection attacks and logs significant events and errors for monitoring and debugging purposes.

By caching and analyzing blockchain transaction data, particularly OP_RETURN outputs, this script aids in uncovering insights and supporting the development of blockchain-based applications for the Martian Republic.

Dependencies:
- Python 3
- PyMySQL
- Marscoin CLI installed and configured
- Access to a MySQL database configured as per the script's DB_CONFIG

Usage:
Ensure the Marscoin CLI is correctly installed and configured, including setting the necessary environment variables for database and CLI paths. Run this script in an environment where Python and the required dependencies are installed. The script can be executed as a cron job or a background process for continuous operation.

Note: This script is part of the Martian Republic project and is tailored for analyzing Marscoin blockchain transactions. It requires access to a running Marscoin node and a configured database for storing transaction data.
"""
import pymysql as MySQLdb
from pymysql.cursors import DictCursor
import sys
import os
import json
import time
from datetime import datetime
from subprocess import Popen, PIPE
from dotenv import load_dotenv
import requests

# Load environment variables
load_dotenv("../.env")

# Set up logging
import logging
from logging.handlers import TimedRotatingFileHandler

# Configure logger
logger = logging.getLogger(__name__)
logger.setLevel(logging.INFO)

# File handler for logging into a file
file_handler = TimedRotatingFileHandler('./track_marscoin.log', when='midnight', interval=1, backupCount=10, encoding='utf-8')
file_handler.setLevel(logging.INFO)
formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')
file_handler.setFormatter(formatter)

# Add the file handler to the logger
logger.addHandler(file_handler)


# Database configuration
DB_CONFIG = {
    'host': os.getenv('DB_HOST'),
    'port': int(os.getenv('DB_PORT')),
    'user': os.getenv('DB_USERNAME'),
    'passwd': os.getenv('DB_PASSWORD'),
    'db': "mrswalletdb"
}

MARSCOIN_EXEC_PATH = os.getenv('MARSCOIN_EXEC_PATH')
MARSCOIN_CONF_PATH = os.getenv('MARSCOIN_CONF_PATH')

def db_connect(attempts=3, delay=5):
    """
    Attempts to connect to the database with a specified number of retries.

    :param attempts: Number of connection attempts
    :param delay: Delay between attempts in seconds
    :return: db connection and cursor if successful, None otherwise
    """
    for attempt in range(attempts):
        try:
            db = MySQLdb.connect(**DB_CONFIG, cursorclass=DictCursor)
            return db, db.cursor()
        except MySQLdb.Error as e:
            logger.error("Database connection error: %s", e)
            if attempt < attempts - 1:  # Not on last attempt
                logger.info("Attempting to reconnect in %s seconds...", delay)
                time.sleep(delay)
            else:
                logger.critical("Failed to connect to the database after %s attempts.", attempts)
    return None, None


def execute_query(cursor, query, params=None, commit=False):
    try:
        cursor.execute(query, params or ())
        if commit:
            cursor.connection.commit()
    except MySQLdb.Error as e:
        logger.error("Query execution error: %s", e)
        cursor.connection.rollback()

def get_current_block():
    try:
        p = Popen([MARSCOIN_EXEC_PATH, "-datadir=" + MARSCOIN_CONF_PATH, "getblockchaininfo"], stdout=PIPE, encoding='utf8')
        output, _ = p.communicate()
        if p.returncode == 0:
            b = json.loads(output)
            return b['blocks'], b['bestblockhash']
        else:
            logger.error("Error getting current block: Non-zero exit code")
    except Exception as e:
        logger.error("Error getting current block: %s", e)
    return None


def get_txs(block_hash):
    """
    Retrieves transactions for a given block hash.
    """
    try:
        command = [MARSCOIN_EXEC_PATH, "-datadir=" + MARSCOIN_CONF_PATH, "getblock", str(block_hash)]
        p = Popen(command, stdout=PIPE, stderr=PIPE, encoding='utf8')
        output, errors = p.communicate()
        if p.returncode == 0:
            b = json.loads(output)
            return b['tx']
        else:
            logger.error("Error getting transactions for hash %s: %s", block_hash, errors)
    except Exception as e:
        logger.error("Exception getting transactions for hash %s: %s", block_hash, e)
    return None


def get_raw_tx(txid):
    """
    Retrieves the raw transaction data for a given transaction ID.
    """
    try:
        command = [MARSCOIN_EXEC_PATH, "-datadir=" + MARSCOIN_CONF_PATH, "getrawtransaction", str(txid)]
        p = Popen(command, stdout=PIPE, stderr=PIPE, encoding='utf8')
        output, errors = p.communicate()
        if p.returncode == 0:
            return output.strip()
        else:
            logger.error("Error getting raw transaction for txid %s: %s", txid, errors)
    except Exception as e:
        logger.error("Exception getting raw transaction for txid %s: %s", txid, e)
    return ""


def get_tx_details(txid):
    """
    Retrieves the transaction details for a given transaction ID.
    """
    try:
        raw_tx = get_raw_tx(txid)
        if raw_tx:
            command = [MARSCOIN_EXEC_PATH, "-datadir=" + MARSCOIN_CONF_PATH, "decoderawtransaction", raw_tx]
            p = Popen(command, stdout=PIPE, stderr=PIPE, encoding='utf8')
            output, errors = p.communicate()
            if p.returncode == 0:
                #print(output)
                return json.loads(output)
            else:
                logger.error("Error decoding transaction for txid %s: %s", txid, errors)
        else:
            logger.warning("No TX data for txid %s", txid)
    except Exception as e:
        logger.error("Exception getting transaction details for txid %s: %s", txid, e)
    return None
 

def load_next_block(cur):
    """
    Fetches the next unprocessed block based on the last entry in feed_log.
    """
    try:
        cur.execute('SELECT * FROM feed_log ORDER BY block DESC LIMIT 1')
        last_processed_block = cur.fetchone()
        
        next_block_height = last_processed_block['block'] + 1 if last_processed_block else 1
        
        # Getting the next block's hash
        command = [MARSCOIN_EXEC_PATH, "-datadir=" + MARSCOIN_CONF_PATH, "getblockhash", str(next_block_height)]
        p = Popen(command, stdout=PIPE, stderr=PIPE, encoding='utf8')
        output, errors = p.communicate()
        
        if p.returncode != 0:
            logger.error(f"Error getting block hash for height {next_block_height}: {errors}")
            return None, None, None

        next_block_hash = output.strip()

        # Getting details for the next block
        command = [MARSCOIN_EXEC_PATH, "-datadir=" + MARSCOIN_CONF_PATH, "getblock", next_block_hash]
        p = Popen(command, stdout=PIPE, stderr=PIPE, encoding='utf8')
        output, errors = p.communicate()
        
        if p.returncode != 0:
            logger.error(f"Error getting details for block hash {next_block_hash}: {errors}")
            return None, None, None

        block_details = json.loads(output)
        mined_date = datetime.fromtimestamp(block_details['time'])

        return block_details['height'], block_details['hash'], mined_date

    except Exception as e:
        logger.error(f"Error loading the next block: {e}")
        return None, None, None



def record_block_processed(cur, db, block, block_hash, mined):
    """
    Records that a block has been processed.
    """
    try:
        insert_query = """
        INSERT INTO feed_log (block, hash, mined, processed_at)
        VALUES (%s, %s, %s, NOW())
        ON DUPLICATE KEY UPDATE processed_at=NOW();
        """
        cur.execute(insert_query, (block, block_hash, mined))
        db.commit()
        logger.info(f"Recorded block {block} as processed.")
    except Exception as e:
        logger.error(f"Error recording block {block} as processed: {e}")
        db.rollback()



def get_user_by_address(cur, address):
    """
    Retrieves a user ID by their wallet address.
    """
    try:
        cur.execute('SELECT * FROM hd_wallet WHERE public_addr = %s LIMIT 1', (address,))
        u = cur.fetchone()
        return u['user_id'] if u else None
    except Exception as e:
        logger.error("Error getting user by address %s: %s", address, e)
        return None


def mark_application_submitted(cur, db, userid):
    """
    Marks in the user's profile that an application has been submitted.
    """
    cur.execute("UPDATE profile SET has_application = 1 WHERE userid = %s", (userid,))
    db.commit() 


# IPFS handling
##################

def fetch_ipfs_data(ipfs_hash):
    """
    Fetches data from an IPFS hash and returns it as a dictionary.
    """
    ipfs_url = f"https://ipfs.marscoin.org/ipfs/{ipfs_hash}"
    response = requests.get(ipfs_url)
    if response.status_code == 200:
        return response.json()
    else:
        logger.error(f"Failed to fetch IPFS data for hash: {ipfs_hash}")
        return None
    

def update_or_insert_applicant(cur, db, application_data, userid):
    """
    Updates or inserts the applicant data into the citizen table.
    """
    # Check if the user already exists in the citizen table
    cur.execute("SELECT id FROM citizen WHERE userid = %s", (userid,))
    result = cur.fetchone()

    if result:
        # Update existing entry
        cur.execute("""UPDATE citizen SET firstname = %s, lastname = %s, displayname = %s, shortbio = %s, avatar_link = %s, liveness_link = %s, public_address = %s, updated_at = NOW() WHERE userid = %s""",
                    (application_data['firstName'], application_data['lastName'], application_data['displayname'], application_data['shortbio'], application_data['picture'], application_data['video'], application_data['addr'], userid))
    else:
        # Insert new entry
        cur.execute("""INSERT INTO citizen (userid, firstname, lastname, displayname, shortbio, avatar_link, liveness_link, public_address, created_at, updated_at) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW())""",
                    (userid, application_data['firstName'], application_data['lastName'], application_data['displayname'], application_data['shortbio'], application_data['picture'], application_data['video'], application_data['addr']))
    
    mark_application_submitted(cur, db, userid)
    logger.info("Application cached successfully")
    db.commit()



#Caching functions
##################

def cache_vote(cur, db, addr, vote, body, userid, txid, block, blockdate):
    proposal = body[1:8].upper()
    link = f'https://ipfs.marscoin.org/ipfs/{body}'
    insert_query = """
    INSERT INTO feed (`address`, `userid`, `tag`, `message`, `embedded_link`, `txid`, `blockid`, `mined`, `updated_at`, `created_at`) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW());
    """
    try:
        # Assuming 'addr' variable should be defined/available in this scope, or passed to this function
        cur.execute(insert_query, (addr, userid, vote, proposal, link, txid, block, blockdate))
        db.commit()
    except Exception as e:
        logger.error("Failed to cache vote: %s", e)

def cache_signed_messages(cur, db, addr, head, body, userid, txid, block, blockdate):
    """
    Cache signed messages in the database.
    """
    link = f'https://ipfs.marscoin.org/ipfs/{body}'
    insert_query = """
    INSERT INTO feed (`address`, `userid`, `tag`, `message`, `embedded_link`, `txid`, `blockid`, `mined`, `updated_at`, `created_at`) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW());
    """
    try:
        cur.execute(insert_query, (addr, userid, head, "Signed Message", link, txid, block, blockdate))
        db.commit()
        logger.info("Successfully cached signed message for txid: %s", txid)
    except Exception as e:
        logger.error("Failed to cache signed message for txid %s: %s", txid, e)
        db.rollback()

def cache_endorsements(cur, db, addr, head, body, userid, txid, block, blockdate):
    """
    Cache endorsements in the database.
    """
    endorsement_info = body  # Placeholder for actual data processing, if needed
    insert_query = """
    INSERT INTO feed (`address`, `userid`, `tag`, `message`, `txid`, `blockid`, `mined`, `updated_at`, `created_at`) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, NOW(), NOW());
    """
    try:
        cur.execute(insert_query, (addr, userid, head, endorsement_info, txid, block, blockdate))
        db.commit()
        logger.info("Successfully cached endorsement for txid: %s", txid)
    except Exception as e:
        logger.error("Failed to cache endorsement for txid %s: %s", txid, e)
        db.rollback()


def cache_general_applications(cur, db, addr, head, body, userid, txid, height, blockdate):
    """
    Cache general public applications
    """
    embedded_link = "https://ipfs.marscoin.org/ipfs/" + body
    message = "General Application"
    insert_query = """
    INSERT INTO feed (`address`, `userid`, `tag`, `message`, `txid`, `blockid`, `mined`, `updated_at`, `created_at`) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, NOW(), NOW());
    """
    logger.info(head)
    logger.info(body)
    try:
        cur.execute(insert_query, (addr, userid, head, message, embedded_link, txid, height, blockdate))
        db.commit()
        logger.info("Successfully cached application for user: %s", userid)
        logger.info("Processing embedded link data...")
        edata = fetch_ipfs_data(embedded_link)
        logger.info("IPFS json data fetched...")
        update_or_insert_applicant(cur, db, edata, userid)
        logger.info("User application data stored in citizen cache table...")
    except Exception as e:
        logger.error("Failed to cache application for txid %s: %s", userid, e)
        db.rollback()

        
def analyze_embedded_data(cur, db, data, addr, txid, height, blockdate, block_hash):
    userid = get_user_by_address(cur, addr)
    if userid is None:
        logger.error("User ID not found for address: %s", addr)
        return
    else:
        logger.info("User: " + str(userid))

    head, body = data.split("_", 1)  # Safely unpack data with a maxsplit=1

    # Mapping head codes to human-readable messages, assuming these messages are used for logging or similar
    head_messages = {
        "GP": "General Public Application",
        "CT": "Citizenship",
        "ED": "Citizenship Endorsement",
        "PRY": "Vote Yes on Proposal",
        "PRN": "Vote No on Proposal",
        "PRA": "Vote Abstain on Proposal",
        "PR": "Voting Proposal launched",
        "SP": "Signed Post",
    }

    logger.info(head_messages.get(head, "Unknown operation"))

    # Directly call cache_vote for relevant operations, avoids repetition and makes future modifications easier
    if head in ["PRY", "PRN", "PRA"]:
        cache_vote(cur, db, addr, head, body, userid, txid, height, blockdate)
    elif head == "ED":
        # Future implementation for cacheEndorsements should follow the same parameter structure for consistency
        cache_endorsements(cur, db, addr, head, body, userid, txid, height, blockdate)
    elif head == "SP":
        cache_signed_messages(cur, db, addr, head, body, userid, txid, height, blockdate)
    elif head == "GP":
        cache_general_applications(cur, db, addr, head, body, userid, txid, height, blockdate)
        


################################################################################
#
#
#   MartianRepublic
#   autoprocess blockchain transactions
#   identify and db cache OP_RETURN codes
#   v.1.0 (05/04/2022)
#
#
#
################################################################################


def process_block_transactions(db, cur, block_hash, height, mined):
    """
    Process transactions for a given block hash.
    """
    block_transactions = get_txs(block_hash)
    logging.info("Found: %d transactions", len(block_transactions))
    for tx in block_transactions:
        transaction = get_tx_details(tx)
        if transaction:
            process_transaction(cur, db, transaction, height, mined, block_hash)


def process_transaction(cur, db, transaction, height, mined, block_hash):
    """
    Process a single transaction, checking for OP_RETURN and other criteria.
    """
    logger.info(f"Processing transaction: {transaction['txid'][:8]} Block: {height}")
    vins = transaction['vin']
    coinbase = vins[0].get('coinbase')
    addr = vins[0].get('addr')

    if coinbase is not None:
        logger.info("Miner transaction. Ignoring...")
        return
    
    print(transaction)

    # Initialize variables to find the vout with the max value that's not OP_RETURN
    # We assume max output is owner of transaction
    max_value = 0
    owner_address = None

    for vo in transaction['vout']:
        script = vo['scriptPubKey']
        if "OP_RETURN" in script['asm']:
            logging.info("We found a notarized transaction")
            data = script['asm'].split(" ")[1]
            byte_array = bytearray.fromhex(data)
            plain = byte_array.decode()
            logging.info("Decoded message: %s", plain)
        else:
            # Check if this output has the highest value so far and capture the address
            if vo['value'] > max_value:
                max_value = vo['value']
                # Extract the address from this output, if available
                addresses = script.get('addresses', [])
                owner_address = addresses[0] if addresses else None

    if owner_address:
        logging.info(f"Transaction initiated by: {owner_address}")
        analyze_embedded_data(cur, db, plain, owner_address, transaction['txid'], height, mined, block_hash)
    else:
        logging.info("Unable to determine transaction initiator.")


def main_loop():
    logger.info("Starting...")
    db, cur = db_connect()
    if not db or not cur:  # Check if initial connection failed
        logger.critical("Initial database connection failed. Exiting.")
        sys.exit(1)

    while True:
        try:
            now = datetime.now()
            height, block_hash, mined = load_next_block(cur)
            if height and block_hash and mined:
                logger.info(f"Next block to process -> Height: {height}, Hash: {block_hash[:8]}, Mined: {mined}")
                process_block_transactions(db, cur, block_hash, height, mined)
                record_block_processed(cur, db, height, block_hash, mined)
                time.sleep(5)
            else:
                logger.info("Waiting for next block...")
                time.sleep(10)  # Delay if there's no new block to process
        except MySQLdb.Error as e:
            logger.error("Database error occurred: %s", e)
            db, cur = db_connect()  # Attempt to reconnect
            if not db or not cur:
                logger.error("Reconnection failed. Exiting loop.")
                break
        except KeyboardInterrupt:
            logger.info("Shutting down...")
            break
        except Exception as e:
            logger.error("An unexpected error occurred: %s", e)
            time.sleep(10)  # Delay before retrying after an error

    if db:
        db.close()  # Ensure the database connection is closed properly on exit



if __name__ == "__main__":
    try:
        main_loop()
    except KeyboardInterrupt:
        logger.info("Shutting down...")
    except Exception as e:
        logger.error("Unexpected error: %s", e)
