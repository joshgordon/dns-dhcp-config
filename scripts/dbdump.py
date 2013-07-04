#!/usr/bin/python

from config import * 
import sys
import os
import shutil
from datetime import datetime
import MySQLdb as mdb

#Adapted from: https://gist.github.com/pklaus/2016269


def printRecord(name, host, ip, mac, comment): 
    print "[{0}]".format(name)
    print "hostname: {0}".format(host)
    print "ip: {0}".format(ip)
    print "mac: {0}".format(mac)
    print "comment: {0}".format(comment)
    print 
    
def printCname(cname, host): 
    print "{0}: {1}".format(cname, host)
     
def main(): 
    #Set up the DB connection 
    con = mdb.connect(dbhost, dbuser, dbpass, dbname)

    with con: 
        cur = con.cursor(mdb.cursors.DictCursor) 
        
        # Get all of the hosts.  
        cur.execute("SELECT * FROM hosts;")

        hosts = cur.fetchall() 

        #Loop through the hosts
        for host in hosts: 
            dhcpname = host["dhcpname"]
            hostname = host["hostname"]
            ip = host["ipaddress"]
            mac = host["mac"]
            comment = host["comment"] 
            printRecord(dhcpname, hostname, ip, mac, comment)


        # Select all the cnames. 
        cur.execute("SELECT * FROM cname;") 
 
        cnames = cur.fetchall() 
        
        print "[cname]"
         
        for cname in cnames:
            printCname(cname["cname"], cname["host"])
 

main() 
