install_sql : build_sql
	mv /etc/dhcp/dhcpd.conf /etc/dhcp/dhcpd.conf.old
	mv /etc/bind/db.gordonclan.net /etc/bind/db.gordonclan.net.old
	mv /etc/bind/db.192 /etc/bind/db.192.old
	cp dhcpd.conf /etc/dhcp/ 
	cp db.gordonclan.net /etc/bind/ 
	cp db.192 /etc/bind/ 
	service isc-dhcp-server restart
	service bind9 restart

build_sql : 
	python makeConfig.py config

build : 
	python makeConfig.py config

install : build
	mv /etc/dhcp/dhcpd.conf /etc/dhcp/dhcpd.conf.old
	mv /etc/bind/db.gordonclan.net /etc/bind/db.gordonclan.net.old
	mv /etc/bind/db.192 /etc/bind/db.192.old
	cp dhcpd.conf /etc/dhcp/ 
	cp db.gordonclan.net /etc/bind/ 
	cp db.192 /etc/bind/ 
	service isc-dhcp-server restart
	service bind9 restart

clean : 
	rm dhcpd.conf
	rm db.192
	rm db.gordonclan.net
