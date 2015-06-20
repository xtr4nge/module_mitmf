#!/bin/bash

echo "installing MITMf..."

apt-get -y install python-twisted
apt-get -y install python-scapy
pip install watchdog
pip install dnslib
pip install IPy
apt-get -y install msgpack-python
apt-get -y install python-requests
pip install dnspython
pip install impacket
pip install requests --upgrade
apt-get -y install python-netaddr
pip install mitmflib
python-flask

#IMAGES (upsidedown)
apt-get -y install libjpeg-dev
pip install --upgrade Pillow

wget https://github.com/xtr4nge/MITMf/archive/master.zip -O MITMf-master.zip
unzip MITMf-master.zip

chmod 755 ./mitmf

mv /usr/share/fruitywifi/www/modules/mitmf/includes/MITMf-master/libs/bdfactory /usr/share/fruitywifi/www/modules/mitmf/includes/MITMf-master/libs/bdfactory.OLD

ln -s /usr/share/fruitywifi/www/modules/bdfproxy/includes/bdf-proxy/bdf/ /usr/share/fruitywifi/www/modules/mitmf/includes/MITMf-master/libs/bdfactory

sed -i 's/port.*=.*53.*# Port to listen on/port     = 5300       # Port to listen on/' MITMf-master/config/mitmf.conf

echo "..DONE.."
exit
