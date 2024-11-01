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
import re
import os
import sys
import json
import math
import time
import requests
import pymysql as MySQLdb
from datetime import datetime
from dotenv import load_dotenv
from pymysql.cursors import DictCursor
from subprocess import Popen, PIPE

# Load environment variables
load_dotenv("../.env")

# Set up logging
import logging
from logging.handlers import TimedRotatingFileHandler

# Configure logger
logger = logging.getLogger(__name__)
logger.setLevel(logging.INFO)
logger.propagate = True

# File handler for logging into a file
file_handler = TimedRotatingFileHandler('./track_marscoin.log', when='midnight', interval=1, backupCount=10, encoding='utf-8')
file_handler.setLevel(logging.INFO)
formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')
file_handler.setFormatter(formatter)

# Add the file handler to the logger
logger.addHandler(file_handler)

# Stream handler for logging to stdout
stream_handler = logging.StreamHandler(sys.stdout)
stream_handler.setLevel(logging.INFO)
stream_handler.setFormatter(formatter)

# Add the stream handler to the logger
logger.addHandler(stream_handler)

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
    """
    Fetches the current blockchain height.
    """
    try:
        command = [MARSCOIN_EXEC_PATH, "-datadir=" + MARSCOIN_CONF_PATH, "getblockchaininfo"]
        p = Popen(command, stdout=PIPE, stderr=PIPE, encoding='utf8')
        output, errors = p.communicate()
        if p.returncode == 0:
            blockchain_info = json.loads(output)
            return blockchain_info.get('blocks', 0)
        else:
            logger.error("Error getting current blockchain height: %s", errors)
    except Exception as e:
        logger.error("Exception getting current blockchain height: %s", e)
    return 0


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
            #logger.error(f"Error getting block hash for height {next_block_height}: {errors}")
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


def get_specific_block_details(cur, block_height):
    # Similar logic to load_next_block but for a specific height
    try:
        command = [MARSCOIN_EXEC_PATH, "-datadir=" + MARSCOIN_CONF_PATH, "getblockhash", str(block_height)]
        p = Popen(command, stdout=PIPE, stderr=PIPE, encoding='utf8')
        output, errors = p.communicate()
        if p.returncode != 0:
            logger.error(f"Error getting block hash for height {block_height}: {errors}")
            return None, None, None

        block_hash = output.strip()
        command = [MARSCOIN_EXEC_PATH, "-datadir=" + MARSCOIN_CONF_PATH, "getblock", block_hash]
        p = Popen(command, stdout=PIPE, stderr=PIPE, encoding='utf8')
        output, errors = p.communicate()
        
        if p.returncode != 0:
            logger.error(f"Error getting details for block hash {block_hash}: {errors}")
            return None, None, None

        block_details = json.loads(output)
        mined_date = datetime.fromtimestamp(block_details['time'])

        return block_details['height'], block_details['hash'], mined_date

    except Exception as e:
        logger.error(f"Error loading block {block_height}: {e}")
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
    Fetches the user ID associated with a given public address from multiple tables.

    Args:
    cur: Database cursor to execute the query.
    address: The public blockchain address of the user.

    Returns:
    The user ID associated with the address or None if not found.
    """
    # Define the queries for each table
    queries = {
        'citizen': "SELECT userid FROM citizen WHERE public_address = %s",
        'civic_wallet': "SELECT user_id FROM civic_wallet WHERE public_addr = %s",
        'hd_wallet': "SELECT user_id FROM hd_wallet WHERE public_addr = %s LIMIT 1"
    }

    # Iterate over the queries dictionary and try to find the user_id
    for table, query in queries.items():
        try:
            cur.execute(query, (address,))
            result = cur.fetchone()
            if result:
                return result['userid'] if 'userid' in result else result['user_id']
        except Exception as e:
            logger.error(f"Error getting user by address {address} from table {table}: {e}")
            # Optionally, you could decide to continue to the next query or return None / raise an error
            # For now, we'll just log the error and continue with the next table

    # If the user_id was not found in any of the tables
    return None


def mark_application_submitted(cur, db, userid):
    """
    Marks in the user's profile that an application has been submitted.
    """
    cur.execute("UPDATE profile SET has_application = 1 WHERE userid = %s", (userid,))
    db.commit() 


def mark_application_processed(cur, db, userid):
    """
    Marks in the user's profile that an application has been completed.
    """
    cur.execute("UPDATE profile SET  general_public = 1, has_application = 0 WHERE userid = %s", (userid,))
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
    

def update_or_insert_applicant(cur, db, addr, application_data, userid):
    """
    Updates or inserts the applicant data into the citizen table.
    """
    # Check if the user already exists in the citizen table
    try:
        cur.execute("SELECT id FROM citizen WHERE userid = %s", (userid,))
        result = cur.fetchone()

        app_data = application_data.get('data', {})
        logger.info("Json fetched: " + str(app_data))
        # Then access the individual data points within this nested dictionary
        firstname = app_data.get('firstName', 'DefaultFirstName')
        lastname = app_data.get('lastName', 'DefaultLastName')
        displayname = app_data.get('displayName', '')  # Corrected key
        shortbio = app_data.get('bio', '')  # Corrected key
        avatar_link = app_data.get('photo', '')  # Corrected key
        liveness_link = app_data.get('video', '')
        public_address = addr  # Assuming addr is defined and available in the scope
        logger.info("nick: " + str(displayname))
        logger.info("avatar: " + str(avatar_link))
        if not avatar_link:
            logger.info("No avatar link found. Can't be. Not overwriting with empty data. Skipping.")
            return None
        if result:
            # Update existing entry
            cur.execute("""UPDATE citizen SET firstname = %s, lastname = %s, displayname = %s, shortbio = %s, avatar_link = %s, liveness_link = %s, public_address = %s, updated_at = NOW() WHERE userid = %s""",
                        (firstname, lastname, displayname, shortbio, avatar_link, liveness_link, public_address, userid))
        else:
            # Insert new entry
            cur.execute("""INSERT INTO citizen (userid, firstname, lastname, displayname, shortbio, avatar_link, liveness_link, public_address, created_at, updated_at) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW())""",
                        (userid, firstname, lastname, displayname, shortbio, avatar_link, liveness_link, public_address))
        
        mark_application_submitted(cur, db, userid)
    except Exception as e:
        logger.error(f"Error retrieving author name for user ID {userid}: {e}")
        return None  
    
    logger.info("Application cached successfully")
    db.commit()

def get_author_name_by_user_id(cur, user_id):
    """
    Fetches the first and last name of a user by their ID from the citizen table.

    Args:
    cur: Database cursor to execute the query.
    user_id: The user ID of the citizen.

    Returns:
    The concatenated first and last name of the user, or None if not found.
    """
    try:
        cur.execute("SELECT firstname, lastname FROM citizen WHERE userid = %s", (user_id,))
        result = cur.fetchone()
        if result:
            # Concatenate first name and last name to form the author's name
            author_name = f"{result['firstname']} {result['lastname']}".strip()
            return author_name
        else:
            return None
    except Exception as e:
        logger.error(f"Error retrieving author name for user ID {user_id}: {e}")
        return None

def find_proposal_id_by_ipfs(cur, ipfs_hash):
    # Note: Adjust the LIKE pattern as needed based on your IPFS URL structure
    search_pattern = f"%{ipfs_hash}"
    cur.execute("SELECT id FROM proposals WHERE ipfs_hash LIKE %s LIMIT 1", (search_pattern,))
    result = cur.fetchone()
    if result:
        return result['id']
    return None

#Caching functions
##################
def cache_vote(cur, db, head, body, txid, height, mined):
    """
    Cache an anonymous vote in the database.

    Args:
    cur: Database cursor to execute the query.
    db: Database connection object for committing the transaction.
    head: The voting indication (PRY, PRN, PRA).
    body: The body of the message containing additional information or the actual vote context.
    txid: Transaction ID of the vote.
    height: The block height at which the vote was mined.
    mined: The timestamp when the block was mined.
    """
    # Convert head to vote representation
    vote_map = {"PRY": "Y", "PRN": "N", "PRA": "A"}
    vote = vote_map.get(head)

    if vote is None:
        logger.error(f"Invalid vote head: {head}")
        return

    # Attempt to find the proposal ID based on the IPFS hash in the body
    proposal_id = find_proposal_id_by_ipfs(cur, body)
    if proposal_id is None:
        logger.error(f"Could not find proposal with IPFS hash: {body}")
        return

    # Prepare and execute the SQL query to insert the vote into the database
    insert_query = """
    INSERT INTO votes (vote, proposal_id, txid, mined, block, created_at) 
    VALUES (%s, %s, %s, %s, %s, NOW());
    """
    try:
        cur.execute(insert_query, (vote, proposal_id, txid, mined, height))
        db.commit()
        logger.info(f"Successfully cached vote for txid: {txid}")
    except Exception as e:
        db.rollback()
        logger.error(f"Failed to cache vote for txid {txid}: {e}")


def cache_signed_messages(cur, db, addr, head, body, userid, txid, block, blockdate):
    """
    Cache signed messages in the database, using the message content from IPFS.
    """
    ipfs_data = fetch_ipfs_data(body)

    # If the IPFS fetch is unsuccessful or the 'post' field is missing, log an error and skip caching
    if not ipfs_data or 'data' not in ipfs_data or 'post' not in ipfs_data['data']:
        logger.error(f"IPFS data not accessible or incomplete for hash: {body}. Skipping caching signed message.")
        return
    
    post_message = ipfs_data['data']['post']
    embedded_link = f'https://ipfs.marscoin.org/ipfs/{body}'
    
    insert_query = """
    INSERT INTO feed (address, userid, tag, message, embedded_link, txid, blockid, mined, updated_at, created_at) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW())
    ON DUPLICATE KEY UPDATE mined = VALUES(mined), blockid = VALUES(blockid), updated_at = NOW();
    """
    try:
        cur.execute(insert_query, (addr, userid, head, post_message, embedded_link, txid, block, blockdate))
        db.commit()
        logger.info("Successfully cached signed message for txid: %s", txid)
    except Exception as e:
        logger.error("Failed to cache signed message for txid %s: %s", txid, e)
        db.rollback()


def insert_citizenship(cur, db, endorsed_address, tag, embedded_link, txid, height, blockdate):
    insert_query = """
    INSERT INTO feed (address, userid, tag, message, embedded_link, txid, blockid, mined, updated_at, created_at) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW())
    ON DUPLICATE KEY UPDATE mined = VALUES(mined), blockid = VALUES(blockid), updated_at = NOW();
    """
    # Find the user ID by the endorsed address
    userid = get_user_by_address(cur, endorsed_address)
    
    # If the userid is not found, log an error and return
    if userid is None:
        logger.error(f"User ID not found for address {endorsed_address}.")
        return
    
    # Default message for citizenship - you can customize this as needed
    message = "Citizenship confirmed via blockchain endorsement."

    try:
        cur.execute(insert_query, (endorsed_address, userid, tag, message, embedded_link, txid, height, blockdate))
        db.commit()
        logger.info(f"Successfully logged citizenship for txid: {txid}")
        update_population_count(cur, db, height, tag)
    except Exception as e:
        logger.error(f"Failed to log citizenship for txid {txid}: {e}")
        db.rollback()

def insert_endorsement(cur, db, addr, userid, tag, message, embedded_link, txid, height, blockdate):
    insert_query = """
    INSERT INTO feed (address, userid, tag, message, embedded_link, txid, blockid, mined, updated_at, created_at) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW())
    ON DUPLICATE KEY UPDATE mined = VALUES(mined), blockid = VALUES(blockid), updated_at = NOW();
    """
    try:
        cur.execute(insert_query, (addr, userid, tag, message, embedded_link, txid, height, blockdate))
        db.commit()
        logger.info(f"Successfully logged endorsement for txid: {txid}")
    except Exception as e:
        logger.error(f"Failed to log endorsement for txid {txid}: {e}")
        db.rollback()

def update_citizenship_status(cur, db, endorsed_address):
    """
    Updates the citizenship status of a user identified by their public address.
    
    Args:
    cur: Database cursor to execute the query.
    db: Database connection object for committing the transaction.
    endorsed_address: The blockchain address of the endorsed user.
    """
    try:
        # Find the user ID by the endorsed address
        userid = get_user_by_address(cur, endorsed_address)
        
        # If the userid is not found, log an error and return
        if userid is None:
            logger.error(f"User ID not found for address {endorsed_address}.")
            return
        
        # Update the profile table for the found userid
        update_query = """
        UPDATE profile 
        SET general_public = 1, citizen = 1, has_application = 0
        WHERE userid = %s
        """
        cur.execute(update_query, (userid,))
        db.commit()
        logger.info(f"Updated citizenship status for user with ID: {userid}")

    except Exception as e:
        logger.error(f"Failed to update citizenship status for address {endorsed_address}: {e}")
        db.rollback()

def process_citizenship(cur, db, endorsed_address, userid, txid, height, blockdate, block_hash, embedded_link):
    # Log citizenship (CT) in the feed, similar to how ED is logged but with CT tag
    logger.info("Processing citizenship...")
    insert_citizenship(cur, db, endorsed_address, 'CT', embedded_link, txid, height, blockdate)

    # Update user's status in the profile table, setting citizenship flag
    logger.info("Updating citizen status in cached db...")
    update_citizenship_status(cur, db, endorsed_address)  


def check_for_citizenship_criteria(cur, endorsed_address):
    """
    Evaluates if an address meets the criteria for citizenship based on the number of endorsements received.
    A member of the public requires endorsements from 10% of existing citizens or a maximum of 5 endorsements,
    whichever is lower, to attain citizenship status.

    Args:
    cur: Database cursor to execute the query.
    endorsed_address: The blockchain address of the user being evaluated for citizenship.

    Returns:
    A boolean indicating whether the address meets the current criteria for citizenship.
    """
    logger.info(f"Checking endorsement count for: {endorsed_address}")
    try:
        # Fetch the total number of citizens from the profile table
        cur.execute("SELECT COUNT(*) as total_citizens FROM profile WHERE citizen = 1")
        result = cur.fetchone()
        total_citizens = result['total_citizens'] if result else 0
        
        # Calculate 10% of existing citizens and use floor to round down
        ten_percent_of_citizens = math.floor(total_citizens * 0.1)

        # Ensure at least 1 endorsement is required if the calculation results in a number between 1 and 2
        max_endorsements_required = max(min(ten_percent_of_citizens, 5), 1)
        
        # Fetch the number of endorsements received by the address
        cur.execute("SELECT COUNT(*) as endorsement_count FROM feed WHERE message = %s AND tag = 'ED'", (endorsed_address,))
        result = cur.fetchone()
        count = result['endorsement_count'] if result else 0
        
        logger.info(f"Public ED Count: {count}")
        
        # Update the endorsement count for the user
        userid = get_user_by_address(cur, endorsed_address)
        cur.execute("UPDATE profile SET endorse_cnt = %s WHERE userid = %s", (count, userid))
        cur.connection.commit()
        
        logger.info(f"Updated endorsement count for {endorsed_address}")
        
        # Check if the number of endorsements meets or exceeds the requirement
        return count >= max_endorsements_required
    except Exception as e:
        logger.error(f"Error checking endorsement count for {endorsed_address}: {e.__class__.__name__}, {e.args}")
        return False



def process_endorsement(cur, db, addr, head, body, userid, txid, height, blockdate, block_hash):
    embedded_link = f"https://ipfs.marscoin.org/ipfs/{body}"
    ipfs_data = fetch_ipfs_data(body)

      
    # Check if IPFS data was successfully fetched
    if not ipfs_data or 'data' not in ipfs_data or 'message' not in ipfs_data['data']:
        logger.error(f"IPFS data not accessible for hash: {body}. Skipping potential endorsement as it might be missing or incomplete.")
        return
     
    message = ipfs_data.get('data', {}).get('message', 'Endorsement')
    
    # Assuming the address format is consistent and can be identified by a regular expression
    # This regex matches the Marscoin address format
    address_pattern = r'M\w{33}'
    addresses = re.findall(address_pattern, message)

    endorsed_address = None
    for address in addresses:
        if address != addr:  # Find the address in the message that's not the endorser's
            endorsed_address = address
            break
    
    if not endorsed_address:
        logger.error("Could not extract endorsed address from message.")
        return

    # Insert ED into feed, now with the extracted endorsed address as part of the message
    insert_endorsement(cur, db, addr, userid, head, endorsed_address, embedded_link, txid, height, blockdate)

    # Check if the endorsed user meets criteria for citizenship (e.g., enough endorsements)
    if check_for_citizenship_criteria(cur, endorsed_address):
        # If criteria met, process citizenship
        process_citizenship(cur, db, endorsed_address, userid, txid, height, blockdate, block_hash, embedded_link)



def update_population_count(cur, db, block, tag):
    """
    Updates the population count in the database based on the transaction tag.
    
    block: The block number in which the transaction was found.
    tag: The tag from the transaction that determines which counter to increment.
    """
    if tag == 'GP':
        update_query = "UPDATE population SET general_public_count = general_public_count + 1 WHERE block = %s"
    elif tag == 'CT':
        update_query = "UPDATE population SET citizen_count = citizen_count + 1 WHERE block = %s"
    else:
        return  # If the tag is neither GP nor CT, do nothing

    try:
        cur.execute(update_query, (block,))
        db.commit()
        logger.info(f"Updated population count for block {block} with tag {tag}.")
    except Exception as e:
        db.rollback()
        logger.error(f"Failed to update population count for block {block} with tag {tag}: {e}")



def cache_general_applications(cur, db, addr, head, body, userid, txid, height, blockdate):
    """
    Cache general public applications
    """
    embedded_link = "https://ipfs.marscoin.org/ipfs/" + body
    message = "General Application"
    insert_query = """
    INSERT INTO feed (`address`, `userid`, `tag`, `message`, `embedded_link`, `txid`, `blockid`, `mined`, `updated_at`, `created_at`) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW())
    ON DUPLICATE KEY UPDATE mined = VALUES(mined), blockid = VALUES(blockid), updated_at = NOW();
    """
    logger.info(head)
    logger.info(body)
    try:
        cur.execute(insert_query, (addr, userid, head, message, embedded_link, txid, height, blockdate))
        db.commit()
        logger.info("Successfully cached application for user: %s", userid)
        logger.info("Processing embedded link data...")
        edata = fetch_ipfs_data(body)
        logger.info("IPFS json data fetched...")
        update_or_insert_applicant(cur, db, addr, edata, userid)
        update_population_count(cur, db, height, head)
        logger.info("User application data stored in citizen cache table...")
    except Exception as e:
        logger.error("Failed to cache application for txid %s: %s", txid, e)
        db.rollback()

    mark_application_processed(cur, db, userid)


def cache_logbook_entry(cur, db, addr, head, body, userid, txid, height, blockdate):
    """
    Cache LogBook entries in the database, updating both feed and publications tables.
    """
    embedded_link = f'https://ipfs.marscoin.org/ipfs/{body}'

    # Insert or update the feed table
    insert_feed_query = """
    INSERT INTO feed (address, userid, tag, message, embedded_link, txid, blockid, mined, updated_at, created_at) 
    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW())
    ON DUPLICATE KEY UPDATE mined = VALUES(mined), blockid = VALUES(blockid), updated_at = NOW();
    """
    try:
        cur.execute(insert_feed_query, (addr, userid, head, "", embedded_link, txid, height, blockdate))
        db.commit()
        logger.info(f"Successfully cached LogBook entry for txid: {txid}")
    except Exception as e:
        logger.error(f"Failed to cache LogBook entry for txid {txid}: {e}")
        db.rollback()

    # Update the publications table if there's a matching ipfs_hash
    update_publications_query = """
    UPDATE publications 
    SET notarized_at = %s, blockid = %s, updated_at = NOW()
    WHERE ipfs_hash = %s;
    """
    try:
        cur.execute(update_publications_query, (blockdate, height, body))
        db.commit()
        if cur.rowcount > 0:  # Check if any row was updated
            logger.info(f"Successfully updated publications entry for IPFS hash: {body}")
        else:
            logger.info(f"No publications entry found to update for IPFS hash: {body}")
    except Exception as e:
        logger.error(f"Failed to update publications entry for IPFS hash {body}: {e}")
        db.rollback()



def cache_voting_proposal(cur, db, addr, head, body, userid, txid, height, blockdate):
    """
    Cache voting proposal in the database and insert corresponding details into the proposals table.
    """
    try:
        embedded_link = f'https://ipfs.marscoin.org/ipfs/{body}'
        ipfs_data = fetch_ipfs_data(body)
        
        if not ipfs_data or 'data' not in ipfs_data:
            logger.error(f"IPFS data not accessible for hash: {body}. Skipping caching voting proposal.")
            return
        
        proposal_data = ipfs_data['data']
        author_name = get_author_name_by_user_id(cur, userid) 
        if not author_name:
            logger.error(f"Author name not found for user ID {userid}.")
            return

        # Insert into feed table
        insert_feed_query = """
        INSERT INTO feed (address, userid, tag, message, embedded_link, txid, blockid, mined, updated_at, created_at) 
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW())
        ON DUPLICATE KEY UPDATE mined = VALUES(mined), blockid = VALUES(blockid), updated_at = NOW();
        """
        try:
            cur.execute(insert_feed_query, (addr, userid, head, proposal_data.get('title', 'Voting Proposal'), embedded_link, txid, height, blockdate))
            db.commit()
            logger.info(f"Successfully cached voting proposal for txid: {txid}")
        except Exception as e:
            logger.error(f"Failed to cache voting proposal for txid {txid}: {e}")
            db.rollback()

        # Attempt to create forum thread
        try:
            forum_thread_query = """
            INSERT INTO forum_threads (category_id, author_id, title, proposal_id, created_at, updated_at) 
            VALUES (2, %s, %s, NULL, NOW(), NOW());
            """
            cur.execute(forum_thread_query, (userid, proposal_data.get('title', 'Voting Proposal')))
            db.commit()
            forum_thread_id = cur.lastrowid
        except Exception as e:
            db.rollback()
            logger.error(f"Failed to create forum thread for proposal: {e}")
            return

        # Attempt to insert proposal with a placeholder for discussion ID
        try:
            insert_proposal_query = """
            INSERT INTO proposals (user_id, title, description, category, participation, duration, threshold, expiration, txid, public_address, ipfs_hash, created_at, updated_at, mined, author, discussion) 
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW(), %s, %s, NULL);
            """
            cur.execute(insert_proposal_query, (
                userid,
                proposal_data.get('title', ''),
                proposal_data.get('description', ''),
                proposal_data.get('category', ''),
                proposal_data.get('participation', 0),
                proposal_data.get('duration', 0),
                proposal_data.get('threshold', 0),
                proposal_data.get('expiration', 0),
                txid,
                addr,
                embedded_link,
                blockdate,
                author_name
            ))
            db.commit()
            proposal_id = cur.lastrowid
        except Exception as e:
            db.rollback()
            logger.error(f"Failed to insert voting proposal into proposals table: {e}")
            return

        # Attempt to update forum thread with the proposal ID and proposal with the forum thread ID (discussion)
        try:
            cur.execute("UPDATE forum_threads SET proposal_id = %s WHERE id = %s", (proposal_id, forum_thread_id))
            cur.execute("UPDATE proposals SET discussion = %s WHERE id = %s", (forum_thread_id, proposal_id))
            db.commit()
        except Exception as e:
            db.rollback()
            logger.error(f"Failed to update forum thread or proposal with IDs: {e}")
            return

        logger.info(f"Successfully processed voting proposal and associated forum thread for txid: {txid}")

    except Exception as e:
        logger.error(f"Unexpected error while processing voting proposal: {e}")
        db.rollback()

        
def analyze_embedded_data(cur, db, data, addr, txid, height, blockdate, block_hash):
    userid = get_user_by_address(cur, addr)
    if userid is None:
        logger.error("User ID not found for address: %s", addr)
        return
    else:
        logger.info("User: " + str(userid))

    logger.info(data)
    if data == "Donation":
        logger.info("Donation seen, ignoring recording...")
        return

    head, body = data.split("_", 1)  # Safely unpack data with a maxsplit=1
    logger.error("split")

    # Mapping head codes to human-readable messages, assuming these messages are used for logging or similar
    head_messages = {
        "GP": "General Public Application",
        "CT": "Citizenship",
        "LB": "Logbook Entry",
        "ED": "Citizenship Endorsement",
        "PR": "Voting Proposal launched",
        "SP": "Signed Post",
    }

    logger.info(head_messages.get(head, "Unknown operation"))

    # Directly call cache_vote for relevant operations, avoids repetition and makes future modifications easier
    if head == "ED":
        process_endorsement(cur, db, addr, head, body, userid, txid, height, blockdate, block_hash)
    elif head == "SP":
        cache_signed_messages(cur, db, addr, head, body, userid, txid, height, blockdate)
    elif head == "GP":
        cache_general_applications(cur, db, addr, head, body, userid, txid, height, blockdate)
    elif head == "LB":
        cache_logbook_entry(cur, db, addr, head, body, userid, txid, height, blockdate)
    elif head == "PR":
        cache_voting_proposal(cur, db, addr, head, body, userid, txid, height, blockdate)


def analyze_embedded_anonymous_data(cur, db, data, txid, height, blockdate, block_hash):
    head, body = data.split("_", 1)  # Safely unpack data with a maxsplit=1
    head_messages = {
        "PRY": "Vote Yes on Proposal",
        "PRN": "Vote No on Proposal",
        "PRA": "Vote Abstain on Proposal",
    }
    logger.info(head_messages.get(head, "Unknown operation"))
    if head in ["PRY", "PRN", "PRA"]:
        cache_vote(cur, db, head, body, txid, height, blockdate)


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
        #logger.info("Miner transaction. Ignoring...")
        return
    
    # Initialize variables to find the vout with the max value that's not OP_RETURN
    # We assume max output is owner of transaction
    max_value = 0
    owner_address = None
    plain = None 
    for vo in transaction['vout']:
        script = vo['scriptPubKey']
        if "OP_RETURN" in script['asm']:
            logging.info("We found a notarized transaction")
            data = script['asm'].split(" ")[1]
            byte_array = bytearray.fromhex(data)
            plain = byte_array.decode()
            logging.info("Decoded message: %s", plain)
        else:
            print("No OPRETURN")
            # Check if this output has the highest value so far and capture the address
            if vo['value'] > max_value:
                max_value = vo['value']
                # Extract the address from this output, if available
                addresses = script.get('addresses', [])
                owner_address = addresses[0] if addresses else None
    
    print("Here")
    if plain and owner_address:
        logger.info(f"Transaction initiated by: {owner_address}")
        return analyze_embedded_data(cur, db, plain, owner_address, transaction['txid'], height, mined, block_hash)
    if plain:
        logger.info("Anonymous data discovered...analyzing...")
        return analyze_embedded_anonymous_data(cur, db, plain, transaction['txid'], height, mined, block_hash)
    else:
        logger.info("Unable to determine transaction initiator.")
        return


def main_loop():
    logger.info("Starting...")
    current_height = get_current_block()  # Get the current network height
    db, cur = db_connect()
    if not db or not cur:  # Check if initial connection failed
        logger.critical("Initial database connection failed. Exiting.")
        sys.exit(1)

    # Check if a specific block height is provided as an argument
    specific_block_height = int(sys.argv[1]) if len(sys.argv) > 1 else None

    while True:
        try:
            if specific_block_height:
                # Process the specific block
                height, block_hash, mined = get_specific_block_details(cur, specific_block_height)
                if height and block_hash and mined:
                    logger.info(f"Processing specified block -> Height: {height}, Hash: {block_hash[:8]}, Mined: {mined}")
                    process_block_transactions(db, cur, block_hash, height, mined)
                    record_block_processed(cur, db, height, block_hash, mined)
                    break  # Exit after processing the specific block
            else:
                # Continue with the next block processing
                height, block_hash, mined = load_next_block(cur)
                if height and block_hash and mined:
                    progress = (height / current_height) * 100 if current_height else 0
                    logger.info(f"Next block to process -> Height: {height}, Hash: {block_hash[:8]}, Mined: {mined}. Progress: {progress:.2f}%")
                    process_block_transactions(db, cur, block_hash, height, mined)
                    record_block_processed(cur, db, height, block_hash, mined)
                else:
                    logger.info("Waiting for next block...")
                    time.sleep(30)  # Delay if there's no new block to process
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
