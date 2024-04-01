#!/usr/bin/python
import MySQLdb
import urllib
import sys
import re
import string, os
import json
import time
import requests
import logging
import _ssl
from datetime import datetime, timedelta
import subprocess
import sys
import os
from os.path import join, dirname
from dotenv import load_dotenv
load_dotenv("../.env")
_ssl.PROTOCOL_SSLv23 = _ssl.PROTOCOL_TLSv1
import logging
from logging.handlers import TimedRotatingFileHandler

logger = logging.getLogger(__name__)
logger.setLevel(logging.INFO)
reason = ""
handler = TimedRotatingFileHandler('./track_marscoin.log', when="d", interval=1, backupCount=5)
handler.setLevel(logging.INFO)

DB_HOST = os.getenv('DB_HOST')
DB_PORT = int(os.getenv('DB_PORT'))
DB_USER = os.getenv('DB_USERNAME')
DB_PASS = os.getenv('DB_PASSWORD')
DB_DATABASE = "mrswalletdb"

MARSCOIN_EXEC_PATH = os.getenv('MARSCOIN_EXEC_PATH')
MARSCOIN_CONF_PATH = os.getenv('MARSCOIN_CONF_PATH')

db = MySQLdb.connect(port=DB_PORT, host=DB_HOST, user=DB_USER, passwd=DB_PASS, db=DB_DATABASE)
cur = db.cursor(MySQLdb.cursors.DictCursor)
cur1 = db.cursor(MySQLdb.cursors.DictCursor)
cur2 = db.cursor(MySQLdb.cursors.DictCursor)
cur3 = db.cursor(MySQLdb.cursors.DictCursor)

def heartbeat(notes = ''):
    global db
    global cur
    if not db.open:
        print ("DB is gone, reconnect")
        db = MySQLdb.connect(port=DB_PORT, host=DB_HOST, user=DB_USER, passwd=DB_PASS, db=DB_DATABASE)
        cur = db.cursor()

    update = "UPDATE script_info SET status = 'enabled', last_updated = NOW(), notes = %s WHERE script_name =  'process_transactions_mars'"
    try:
        cur.execute(update, (notes,))
        db.commit()
    except:
        reason = 'Error: %s - %s' % sys.exc_info()[:2]
        print (reason)

def getCurrentBlock():
    p = subprocess.Popen([str(MARSCOIN_EXEC_PATH), "-datadir="+MARSCOIN_CONF_PATH, "getblockchaininfo"], stdout=subprocess.PIPE, encoding='utf8')
    output, err = p.communicate()
    b = json.loads(output)
    return b['blocks'], b['bestblockhash']

def getTxs(hash):
    p = subprocess.Popen([str(MARSCOIN_EXEC_PATH), "-datadir="+MARSCOIN_CONF_PATH, "getblock", str(hash)], stdout=subprocess.PIPE, encoding='utf8')
    output, err = p.communicate()
    b = json.loads(output)
    return b['tx']

def getRawTx(txid):
    p = subprocess.Popen([str(MARSCOIN_EXEC_PATH), "-datadir="+MARSCOIN_CONF_PATH, "getrawtransaction", str(txid)], stdout=subprocess.PIPE, encoding='utf8')
    output, err = p.communicate()
    raw_tx = output.strip()
    return raw_tx

def getTxDetails(txid):
    print("Txid: " + str(txid))
    try:
        raw_tx = getRawTx(txid)
        if raw_tx != "":
            p = subprocess.Popen([str(MARSCOIN_EXEC_PATH), "-datadir="+MARSCOIN_CONF_PATH, "decoderawtransaction", raw_tx], stdout=subprocess.PIPE, encoding='utf8')
            output, err = p.communicate()
            tx = json.loads(output)
            return tx
        return ""
    except:
        #reason = 'Error: %s - %s' % sys.exc_info()[:2]
        print ("No TX data") 

def loadLatestBlock():
    height = 0
    hash = ""
    cur.execute('select * from feed_log order by id desc limit 1')
    b = cur.fetchone()
    return b['block'], b['hash'],b['mined']

def getUserByAddress(address):
    cur.execute('select * from hd_wallet where public_addr = %s LIMIT 1', (address))
    u = cur.fetchone()
    return u['user_id']


def cacheSignedMessages(head, body, userid, txid, block, blockdate):
    print("cached online at the moment. to be implemented.")
    # resp = requests.get(url="https://ipfs.marscoin.org/ipfs/" + body)
    # sm = resp.json()
    # print(sm.data.post)
    # insert = "INSERT INTO feed (`address`, `userid`, `tag`, `message`, `embedded_link`, `txid`, `blockid`, `mined`, `updated_at`, `created_at`) VALUES ('MRKAuE7k9UhANQ8JjoU5A9KACic5Rt2Diz', userid, 'SP', sm.data.post, 'https://ipfs.marscoin.org/ipfs/'+body, '12a93f3899b58eac1880766d4fde1fd3ffe1fd99dc9eab5c6b40aaffe76a16ec', '1594109', '2022-02-26 17:22:47', NOW(), NOW());"

def cacheVote(vote, body, userid, txid, block, blockdate):
    proposal = body[1:8].upper()
    link = 'https://ipfs.marscoin.org/ipfs/'+body
    insert = "INSERT INTO feed (`address`, `userid`, `tag`, `message`, `embedded_link`, `txid`, `blockid`, `mined`, `updated_at`, `created_at`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, NOW(), NOW());"
    try:
        cur.execute(insert, (addr, userid, vote, proposal, link, txid, block, blockdate ))
        db.commit()

    except:
        reason = 'Error: %s - %s' % sys.exc_info()[:2]
        print (reason)
        logger.info(reason)
        heartbeat(reason)

def cacheEndorsements(head, body, userid, txid, block, blockdate):
    print("to be implemented")

        
def analyzeEmbeddedData(data, addr, txid, block, blockdate):
    #find user for this message by public address
    userid = getUserByAddress(addr)
    head = data.split("_")[0]
    body = data.split("_")[1]
    if head == "GP":
        print("General Public Application")
    if head == "CT":
        print("Citizenship")
    if head == "ED":
        print("Citizenship Endorsement")
        cacheEndorsements()
    if head == "PRY":
        print("Vote Yes on Proposal")
        cacheVote(head, body, userid, txid, block, blockdate)
    if head == "PRN":
        print("Vote No on Proposal")
        cacheVote(head, body, userid, txid, block, blockdate)
    if head == "PRA":
        print("Vote Abstain on Proposal")
        cacheVote(head, body, userid, txid, block, blockdate)
    if head == "PR":
        print("Voting Proposal launched")
    if head == "SP":
        print("Signed Post")
        #cacheSignedMessages(head, body, userid, txid, block, blockdate)


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

while True:

    if not db.open:
        print ("DB is gone, reconnect")
        db = MySQLdb.connect(port=DB_PORT, host=DB_HOST, user=DB_USER, passwd=DB_PASS, db=DB_DATABASE)
        cur = db.cursor(MySQLdb.cursors.DictCursor)
        cur1 = db.cursor(MySQLdb.cursors.DictCursor)

    now = datetime.now()
    heartbeat()

    height, hash, mined = loadLatestBlock()
    print(height)
    print(hash)
    print(mined)

    block_transactions = getTxs(hash)
    print("Found: " + str(len(block_transactions)))
    for tx in block_transactions:
        transaction = getTxDetails(tx)
        vouts = transaction['vout']
        vins = transaction['vin']
        txid = transaction['txid']
        addr = vins[0]['addr']
        for vo in vouts:
            #print(vo['scriptPubKey'])
            script = vo['scriptPubKey']
            if "OP_RETURN" in script['asm']:
                print("We found a notarized transaction")
                print(script['asm'])
                print(script['type'])
                data = script['asm']
                message = data.split(" ")[1]
                byte_array = bytearray.fromhex(message)
                plain = byte_array.decode()
                print(plain)
                analyzeEmbeddedData(plain, addr, txid, height, mined)
            else:
                print("Regular output")
            time.sleep(2)

    # try:
    #     cur.execute(update)
    #     db.commit()

    # except:
    #     reason = 'Error: %s - %s' % sys.exc_info()[:2]
    #     print (reason)
    #     logger.info(reason)
    #     heartbeat(reason)
    #     sys.exit(-1)


    time.sleep(10)

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

