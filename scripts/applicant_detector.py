#!/usr/local/bin/python3
import pymysql as MySQLdb
import time
import sys
import os
from os.path import join, dirname
from dotenv import load_dotenv

load_dotenv("../.env")

DB_HOST = os.getenv('DB_HOST')

DB_PORT = int(os.getenv('DB_PORT'))
DB_USER = os.getenv('DB_USERNAME')
DB_PASS = os.getenv('DB_PASSWORD')
DB_DATABASE = "mrswalletdb"

try:
    db = MySQLdb.connect(port=DB_PORT, host=DB_HOST, user=DB_USER, passwd=DB_PASS, db=DB_DATABASE)
    cur = db.cursor(MySQLdb.cursors.DictCursor)
    cur1 = db.cursor(MySQLdb.cursors.DictCursor)


    #
    #   Find applicants with unfinished (unnotarized) applications and make them visible to the users of the Martian Republic to encourage completion of their application process
    #
    subfolders = [ f.name for f in os.scandir("../public/assets/citizen") if f.is_dir() ]
    for s in subfolders:
        print("Checking address " + str(s))
        sql = "SELECT * from feed where address = %s"
        cur1.execute(sql, (s))  
        entries = cur1.fetchall()
        if cur1.rowcount == 0:
            print("Enter applicant " + str(s))
            try:
                sql = "UPDATE profile, hd_wallet SET has_application = 1 WHERE profile.userid = hd_wallet.user_id AND hd_wallet.public_addr = %s "
                cur.execute(sql, (s))
                db.commit()
                time.sleep(1)
                print("Added applicant to db ... " + str(s))
            except Exception as e:
                reason = 'Error: %s - %s' % sys.exc_info()[:2]
                print(reason)
        else:
            print("Address found in feed table")

    #
    #   Find applicants that did notarize their appliation and remove their applicant flag
    #
    sql = "SELECT DISTINCT(profile.userid) FROM feed, profile, hd_wallet WHERE feed.address = hd_wallet.public_addr AND hd_wallet.user_id = profile.userid AND profile.has_application = 1"
    cur1.execute(sql)  
    entries = cur1.fetchall()
    for e in entries:
        print("Checking applicant Userid: " + str(e['userid']))
        try:
            sql = "UPDATE profile  SET has_application = 0 WHERE profile.userid = %s "
            cur.execute(sql, (e['userid']))
            db.commit()
            time.sleep(1)
            print("Removed applicant flag from db ... for user " + str(e['userid']) + " - " + str(s))
        except Exception as e:
            reason = 'Error: %s - %s' % sys.exc_info()[:2]
            print(reason)

    cur.close()
    cur1.close()
    db.close()

except Exception as e:
    reason = 'Error: %s - %s' % sys.exc_info()[:2]
    print(reason)
