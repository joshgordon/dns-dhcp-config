#!/usr/bin/python
# netMySQL.py
# This is the interface for the mysql database. All of the talking to the mysql
# database is done through the functions in this file. 
# 
# Copyright 2014 Josh Gordon <code@joshgordon.net> 
# All code is released under GPL v2. 
# 
# The following functions are implemented in this file. A short description is 
# located in this header, for your convinence. All real functions (not __enter__
# or __exit__) return a list or tuple of dictionaries. 
# 
# getHosts() - returns all of the hosts. 
# getCnames() - returns all of the cnames. 
# getAdditional() - returns all of the additional records. 


from config import * 
import MySQLdb as mdb


class database: 
  """Talks to the database.""" 


################################################################################
# Set up the database connection.   
  def __enter__(self): 
    self.con = mdb.connect(dbhost, dbuser, dbpass, dbname) 
    self.cur = self.con.cursor(mdb.cursors.DictCursor) 


################################################################################
# Tear down the db connection.     
  def __exit__(self, type, value, traceback): 
    self.cur.close() 
    self.con.close() 
    
    
################################################################################
# Return all the hosts as a list of dictionaries. 
  def getHosts(self): 
    # Get all of the hosts.  
    self.cur.execute("SELECT * FROM hosts order by ipaddress;")
    
    hosts = self.cur.fetchall() 
    return hosts 

################################################################################
# Return all the cnames as a list of dictionaries. 
  def getCnames(self): 
    # Select all the cnames. 
    self.cur.execute("SELECT * FROM cname;") 
    cnames = self.cur.fetchall() 
    return cnames 

################################################################################
# Gets all the additional records. Returns a list/tuple of dictionaries. 
  def getAdditional(self): 
    # Grab all the additional records: 
    self.cur.execute("SELECT * FROM additional_records;")
    additional = self.cur.fetchall()
    return additional
        

