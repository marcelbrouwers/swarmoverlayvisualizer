#!/usr/bin/env python
import nmap
import netifaces as ni
import csv
import socket
import StringIO
import requests
import json
from netaddr import IPAddress

hostname = socket.gethostname()

for interface in ni.interfaces():
    ni.ifaddresses(interface)
    ip = ni.ifaddresses(interface)[2][0]['addr']
    netmask = ni.ifaddresses(interface)[2][0]['netmask']
    cidr = IPAddress(netmask).netmask_bits()
    if ip != '127.0.0.1' and not ip.startswith('172.18'): #limit to the docker swarm overlay networks

        nm = nmap.PortScanner()
        network = str(ip) + '/' + str(cidr)
        nm.scan(hosts=network, arguments='')
        reader = csv.reader(StringIO.StringIO(nm.csv()), delimiter=';')
        for line in reader:
            if line[0] != 'host':
                print line[0],line[3],line[4],line[6]
            data = {}
            data['ip'] = line[0]
            data['protocol'] = line[3]
            data['port'] = line[4]
            data['host'] = hostname
            json_data = json.dumps(data, ensure_ascii='False')
            r = requests.post('http://bastia.studlab.os3.nl/rp2/openportreporternmap.php', verify=False, json=json_data)
            headers = {'Content-type': 'application/json'}

