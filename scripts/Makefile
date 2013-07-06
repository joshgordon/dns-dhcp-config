install_sql : build_sql
	mv /etc/dhcp/dhcpd.conf /etc/dhcp/dhcpd.conf.old
	mv /etc/bind/db.gordonclan.net /etc/bind/db.gordonclan.net.old
	mv /etc/bind/db.192 /etc/bind/db.192.old
	cp dhcpd.conf /etc/dhcp/ 
	cp db.gordonclan.net /etc/bind/ 
	cp db.192 /etc/bind/ 
	service isc-dhcp-server reload
	service bind9 reload

build_sql : 
	python makeFromDB.py


clean : 
	rm db.*
	rm dhcpd.conf
