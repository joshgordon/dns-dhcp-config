#!/usr/bin/python
# makeFromDB.py 
# This file will load a dns and dhcp configuration from a database, then creates
# valid configuration files from said database. The database is a single 
# external library so that it can easily be replaced by another. 
# 
# Copyright 2014 Josh Gordon <code@joshgordon.net> 
# All code is released under GPL v2. 
# 

from config import * 
import sys
import os
import shutil
from datetime import datetime
from netMySQL import db

#Adapted from: https://gist.github.com/pklaus/2016269


################################################################################
# Write out the header for the DNS zone file. This should be called before 
# writing out any dns zone records. 
# 
def buildDNSHead(file):

    # Configuration options 
    host = domain 
    first_name_server = nameserver.split('.')[0] + '.' 
    administrative_contact = "hostmaster."
    record_ttl = "1h"
    zone_serial = datetime.now().strftime("%Y%m%d%H%M%S")
    slave_refresh_interval = "1h"
    slave_retry_interval = "15m"
    slave_expiration_time = "1w"
    nxdomain_cache_time = "1h"
   
    # Write a warning for the next person to not edit this file by hand.  
    file.write(";!!!!!!!!!!!!!!!!!!!!!!!!NOTICE!!!!!!!!!!!!!!!!!!!!!!!!!\n")
    file.write(";DO NOT EDIT THIS FILE BY HAND. IT WILL BE OVERWRITTEN.!\n")
    file.write(";!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n") 

    
    file.write("$TTL {0}\t; Default TTL\n".format(record_ttl))
    
    file.write("@\tIN\tSOA\t{0}\t{1} (\n".format(first_name_server, administrative_contact))
    file.write("\t{0}\t; serial\n".format(zone_serial))
    file.write("\t{0}\t; slave refresh interval\n".format(slave_refresh_interval)) 
    file.write("\t{0}\t; slave retry interval\n".format(slave_retry_interval)) 
    file.write("\t{0}\t; slave copy expire time\n".format(slave_expiration_time))
    file.write("\t{0}\t; NXDOMAIN cache time\n".format(nxdomain_cache_time))
    file.write("\t)\n\n\n")

################################################################################
# Add a forward DNS record to a dns zone file. Takes the file handle, hostname, 
# ip address, and the comment. 
#
def addForwardDNS(file, host, IP, comment): 
    file.write("%-24sIN\tA\t%s" % (host, IP))
    if (comment != ""): 
        file.write("; {0}".format(comment))
    file.write("\n") 

################################################################################
# Adds a cname to the dns zone file. Takes file handle, hostname, and alias. 
# Alias is what the new cname record should point to. 
#
def addCNAME(file, host, alias): 
    file.write("%-24sIN\tCNAME\t%s\n" % (host, alias))

################################################################################
# Adds a reverse DNS record to the reverse zone file. Takes the full IP and 
# automatically rips out the last octet.  
#
def addReverseDNS(file, host, ip, comment): 
    file.write("{0}\tIN\tPTR\t{1}.{2}.".format(str(int(ip.split('.')[3])), host, domain))
    if (comment != ""): 
        file.write(";{0}".format(comment))
    file.write("\n")


################################################################################
# Adds any kind of dns record to a dns zone file. Useful for SRV records, 
# TXT records, etc. 
# 
def addAdditionalDNS(file, host, type, value): 
    file.write("%-24s%-8s%-8s%s" % (host, "IN", type, value))
    file.write("\n")
    
################################################################################
# Adds a record to DHCP. uniqueName (as the name suggests) has to be unique
# in order for isc-dhcp-server to be happy. 
# 
def addDHCP(file, uniqueName, mac, ip, comment, host): 
    if (comment != ""): 
        file.write("\n#{0}\n".format(comment))
    file.write("\thost {0} {{ \n\t\thardware ethernet {1};\n\t\tfixed-address {2};\n".format(uniqueName, mac, ip))
    file.write("""\t\toption host-name "{0}.{1}";\n\t}}\n""".format(host, domain))

################################################################################
# Adds a host to a host file. (to be put in /etc/hosts, or 
# C:\Windows\System32\Drivers\etc\hosts.) Useful if for some stupid reason your
# DNS server isn't reliable. 
# 
def addHost(file, ip, host):
    file.write("%-16s %s %s.%s\n" % (ip, host, host, domain))

################################################################################
# Sanitizes IP input. 
# http://xkcd.com/327 (little bobby tables.) 
#
def cleanIP(ip): 
    ipAddr = ip.split('.')
    newIP = list() 
    for octet in ipAddr:
        octet = int(octet)
        if octet < 0 or octet > 255:  
            raise ValueError(ip + ' is not a valid ip address, skipping!') 
        newIP.append(int(octet))
    return '.'.join(map(str, newIP))

################################################################################
# Main
# Pretty self explanitory. 
def main(): 
    #Set up the DB connection 
    con = db.db()

    with db: 
        #Compute the file names. 
        dns_file="db." + domain 
        rdns_file="db." + rdns_ip.split('.')[0]


        #Remove the existing config files. 
        try: 
            os.remove('/tmp/%s' % dns_file)
            os.remove('/tmp/%s' % rdns_file)
            os.remove('/tmp/dhcpd.conf')
	    os.remove('/tmp/hosts') 
        
        except: 
            print "One or more config files not found. Recreating." 
    
        #copy the head of the dhcp config file. 
        shutil.copy('dhcpd.conf.top', '/tmp/dhcpd.conf')
    
        #Open the files for writing: 
        db_rdns = file('/tmp/%s' % rdns_file, 'w') 
        db_domain = file('/tmp/%s' % dns_file, 'w') 
        dhcpdconf = file('/tmp/dhcpd.conf', 'a') 
	hostsFile = file('/tmp/hosts', 'w')
    
        #build the top part of the dhcp files. 
        buildDNSHead(db_rdns)
        db_rdns.write("{0}.{1}.{2}.in-addr.arpa. \t IN \t NS \t {3}.\n".format(rdns_ip.split('.')[2], rdns_ip.split('.')[1], rdns_ip.split('.')[0], nameserver))

        buildDNSHead(db_domain) 
        db_domain.write("@ \t\t\tIN \tNS \t{0}.\n".format(nameserver)) 

        hosts = db.getHosts()

        #Loop through the hosts
        for host in hosts: 
            try: 
                dhcpname = host["dhcpname"]
                hostname = host["hostname"]
                ip = cleanIP(host["ipaddress"])
                mac = host["mac"]
                comment = host["comment"]
                addForwardDNS(db_domain, hostname, ip, comment)
                if(mac != "00:00:00:00:00:00"): 
                    addReverseDNS(db_rdns, hostname, ip, comment) 
                    addDHCP(dhcpdconf, dhcpname, mac, ip, comment, hostname)
                    if hostname != "@": 
                        addHost(hostsFile, ip, hostname)
            except ValueError as e: 
                print '[\033[91m!!\033[0m]', 
                print e


        cnames = db.getCnames()
         
        for cname in cnames:
            addCNAME(db_domain, cname["cname"], cname["host"])
 
        dhcpdconf.write("}\n"); 
        
        additional = getAdditional() 

        for record in additional: 
            addAdditionalDNS(db_domain, record["host"], record["type"], record["value"])
        
        db_rdns.close() 
        db_domain.close() 
        dhcpdconf.close() 


main() 
