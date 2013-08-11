#!/bin/sh
echo Generating new configuration files from the mySQL database. 
cd /var/www/network/scripts
python makeFromDB.py

echo Backing up old files
mv /etc/dhcp/dhcpd.conf /etc/dhcp/dhcpd.conf.old
mv /etc/bind/db.gordonclan.net /etc/bind/db.gordonclan.net.old
mv /etc/bind/db.192 /etc/bind/db.192.old

echo Copying new files into place
cp /tmp/dhcpd.conf /etc/dhcp/ 
cp /tmp/db.gordonclan.net /etc/bind/ 
cp /tmp/db.192 /etc/bind/ 

echo Deleting temp files
rm /tmp/dhcpd.conf
rm /tmp/db.*

echo Reloading configuration
service isc-dhcp-server reload
service bind9 reload

echo done\!
