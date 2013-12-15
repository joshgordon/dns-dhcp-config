#!/usr/bin/python

from config import * 
import sys
import os
import shutil
from datetime import datetime
import MySQLdb as mdb


def main(): 
  if (len(sys.argv) < 2): 
    print "error"
    exit(1) 
  else: 
    hostname = sys.argv[1]
  
  #Set up the DB connection 
  con = mdb.connect(dbhost, dbuser, dbpass, dbname)

  with con: 
    cur = con.cursor(mdb.cursors.DictCursor) 
        
    # Get all of the hosts.  
    cur.execute("""SELECT mac FROM hosts where hostname=%s;""", (hostname))

    mac = cur.fetchone()['mac'] 
    
    os.system("wakeonlan " +  mac)

main() 
