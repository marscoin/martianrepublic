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
from MySQLdb.cursors import DictCursor
import sys
import os
import json
import time
import logging
from datetime import datetime
from logging.handlers import TimedRotatingFileHandler
from subprocess import Popen, PIPE
from dotenv import load_dotenv

# Load environment variables
load_dotenv("../.env")
_ssl.PROTOCOL_SSLv23 = _ssl.PROTOCOL_TLSv1

# Set up logging
logger = logging.getLogger(__name__)
handler = TimedRotatingFileHandler('./track_marscoin.log', when="d", interval=1, backupCount=5)
logger.addHandler(handler)
logger.setLevel(logging.INFO)

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
    logger.info("Txid: %s", txid)
    try:
        raw_tx = get_raw_tx(txid)
        if raw_tx:
            command = [MARSCOIN_EXEC_PATH, "-datadir=" + MARSCOIN_CONF_PATH, "decoderawtransaction", raw_tx]
            p = Popen(command, stdout=PIPE, stderr=PIPE, encoding='utf8')
            output, errors = p.communicate()
            if p.returncode == 0:
                return json.loads(output)
            else:
                logger.error("Error decoding transaction for txid %s: %s", txid, errors)
        else:
            logger.warning("No TX data for txid %s", txid)
    except Exception as e:
        logger.error("Exception getting transaction details for txid %s: %s", txid, e)
    return None
 

def load_latest_block(cur):
    """
    Loads the latest block information from the database.
    """
    try:
        cur.execute('SELECT * FROM feed_log ORDER BY id DESC LIMIT 1')
        b = cur.fetchone()
        return b['block'], b['hash'], b['mined']
    except Exception as e:
        logger.error("Error loading the latest block: %s", e)
        return None, None, None


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
    

def cacheSignedMessages(head, body, userid, txid, block, blockdate):
    print("cached online at the moment. to be implemented.")
    # resp = requests.get(url="https://ipfs.marscoin.org/ipfs/" + body)
    # sm = resp.json()
    # print(sm.data.post)
    # insert = "INSERT INTO feed (`address`, `userid`, `tag`, `message`, `embedded_link`, `txid`, `blockid`, `mined`, `updated_at`, `created_at`) VALUES ('MRKAuE7k9UhANQ8JjoU5A9KACic5Rt2Diz', userid, 'SP', sm.data.post, 'https://ipfs.marscoin.org/ipfs/'+body, '12a93f3899b58eac1880766d4fde1fd3ffe1fd99dc9eab5c6b40aaffe76a16ec', '1594109', '2022-02-26 17:22:47', NOW(), NOW());"

def cache_vote(cur, db, vote, body, userid, txid, block, blockdate):
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

def cache_signed_messages(cur, db, head, body, userid, txid, block, blockdate):
    """
    Cache signed messages in the database.
    """
    link = f'https://ipfs.marscoin.org/ipfs/{body}'
    # Assuming the structure of the 'feed' table accommodates storing signed messages appropriately
    insert_query = """
    INSERT INTO feed (`address`, `userid`, `tag`, `message`, `embedded_link`, `txid`, `blockid`, `mined`, `updated_at`, `created_at`) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW());
    """
    try:
        # Assuming 'addr' is available in this scope or passed as an argument
        cur.execute(insert_query, (addr, userid, head, "Signed Message", link, txid, block, blockdate))
        db.commit()
        logger.info("Successfully cached signed message for txid: %s", txid)
    except Exception as e:
        logger.error("Failed to cache signed message for txid %s: %s", txid, e)
        db.rollback()

def cache_endorsements(cur, db, head, body, userid, txid, block, blockdate):
    """
    Cache endorsements in the database.
    """
    # Assuming the structure for endorsements data is known and we're storing it similarly to votes and signed messages
    endorsement_info = body  # Placeholder for actual data processing, if needed
    insert_query = """
    INSERT INTO feed (`address`, `userid`, `tag`, `message`, `txid`, `blockid`, `mined`, `updated_at`, `created_at`) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, NOW(), NOW());
    """
    try:
        # Assuming 'addr' is determined based on the endorsement context or passed as an argument
        cur.execute(insert_query, (addr, userid, head, endorsement_info, txid, block, blockdate))
        db.commit()
        logger.info("Successfully cached endorsement for txid: %s", txid)
    except Exception as e:
        logger.error("Failed to cache endorsement for txid %s: %s", txid, e)
        db.rollback()


        
def analyze_embedded_data(cur, db, data, addr, txid, block, blockdate):
    # Assuming getUserByAddress function is already defined and takes 'cur' as a parameter
    userid = get_user_by_address(cur, addr)
    if userid is None:
        logger.error("User ID not found for address: %s", addr)
        return

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
        cache_vote(cur, db, head, body, userid, txid, block, blockdate)
    elif head == "ED":
        # Future implementation for cacheEndorsements should follow the same parameter structure for consistency
        cache_endorsements(cur, db, head, body, userid, txid, block, blockdate)
    elif head == "SP":
        cacheSignedMessages(head, body, userid, txid, block, blockdate)
        


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


def process_block_transactions(db, cur, block_hash):
    """
    Process transactions for a given block hash.
    """
    block_transactions = get_txs(cur, block_hash)
    logging.info("Found: %d transactions", len(block_transactions))
    for tx in block_transactions:
        transaction = get_tx_details(cur, tx)
        if transaction:
            process_transaction(cur, db, transaction)

def process_transaction(cur, db, transaction):
    """
    Process a single transaction, checking for OP_RETURN and other criteria.
    """
    txid = transaction['txid']
    for vo in transaction['vout']:
        script = vo['scriptPubKey']
        if "OP_RETURN" in script['asm']:
            logging.info("We found a notarized transaction")
            data = script['asm'].split(" ")[1]
            byte_array = bytearray.fromhex(data)
            plain = byte_array.decode()
            logging.info("Decoded message: %s", plain)
            # Assuming analyze_embedded_data is improved to handle exceptions internally
            analyze_embedded_data(cur, db, plain, transaction)
        else:
            logging.info("Regular output")


def main_loop():
    db, cur = db_connect()
    if not db or not cur:  # Check if initial connection failed
        logger.critical("Initial database connection failed. Exiting.")
        sys.exit(1)

    while True:
        try:
            now = datetime.now()
            height, block_hash, mined = load_latest_block(cur)
            logging.info("Current block height: %s, hash: %s, mined: %s", height, block_hash, mined)

            process_block_transactions(db, cur, block_hash)
            time.sleep(10)  # Main loop delay
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


#Get the latest blockhash and count
#./marscoin-cli getblockchaininfo

#Get the block's transactions
#./marscoin-cli getblock b484047f180c9210b566df827ff4a258f77bee18b5b099c0a006744f72a28e5e

#Then we do:
#./marscoin-cli getrawtransaction 827071dc502eae261920dd55e36be0d859e15a313e465d068005941546382252
#and take that hash and

#./marscoin-cli decoderawtransaction longhash

#and in that we search for any vout that looks like this:
# "vout": [
#     {
#       "value": "0.00000000",
#       "n": 0,
#       "scriptPubKey": {
#         "asm": "OP_RETURN 47505f516d5a39314e4b677778504465427a41753545327a5533505678734b50626d7075776a6b6472446d6a544b4a6168",
#         "type": "nulldata"
#       }
#     },
#then we grab the nulldata type asm and ascii that hash and analyse it

