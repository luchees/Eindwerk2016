#!/usr/bin/python
import sys
import re
from netmiko import ConnectHandler

#cisco asa instellingen
#TODO rsa key of username en password in andere file
cisco_5505 = {'device_type':'cisco_asa', 'ip':'193.191.187.41', 'username':'ucll', 'password': 'ucllwachtwoord', 'secret':'secret', 'verbose': True,}

net_connect = ConnectHandler(**cisco_5505) #ssh connection opstarten met de cisco_5505

net_connect.enable() # enable commando
net_connect.send_command('\n') #enable password
net_connect.send_command('conf t')# execute commando
command = ' '.join( sys.argv[1:])
output=net_connect.send_command(command) #all except first
newoutput = output.replace("-", "")


print newoutput

