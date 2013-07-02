#!/usr/bin/python

from config import * 
import ConfigParser
import sys
import os
import shutil
from datetime import datetime
import MySQLdb as mdb

def main(): 
    #Check for right number of command line args.
    if (len(sys.argv) != 2): 
        print "Usage: " + sys.argv[0] + " <config file>" 
        sys.exit(1) 
        
    filename = sys.argv[1] 
    
    
    config = ConfigParser.ConfigParser() 
    config.read(filename) 
    
    #Set up the database
    con = mdb.connect(dbhost, dbuser, dbpass, dbname)

    with con: 
        cur = con.cursor()
        cur.execute("DROP TABLE IF EXISTS hosts;") 
        cur.execute("create table hosts  (dhcpname varchar(64) PRIMARY KEY, hostname varchar(64), ipaddress varchar(15), mac varchar(35));")
        cur.execute("DROP TABLE IF EXISTS cname;")
        cur.execute("create table cname (cname varchar(64) PRIMARY KEY, host varchar(64));")
       
         
        #Loop through the hosts
        for section in config.sections(): 
            if (section != "CNAME" and section != "cname"): 
                host = config.get(section, "hostname")
                ip = config.get(section, "ip")
                mac = config.get(section, "mac")

                cur.execute("INSERT INTO hosts (dhcpname, hostname, ipaddress, mac) VALUES('" + section + "', '" + host + "', '" + ip + "', '" + mac + "');")
        
            else: 
                cnames = config.items(section)
                for cname in cnames:
                    cur.execute("INSERT INTO cname (cname, host) values('" + cname[0] + "', '" + cname[1] + "');")


main() 
