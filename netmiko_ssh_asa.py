#!/usr/bin/python
import sys
import re
from netmiko import ConnectHandler

if (sys.argv[1]=="193.191.187.44"):
	cisco_5505 = {'device_type':'cisco_asa', 'ip':'193.191.187.44', 'username':'ucll', 'password': 'ucllwachtwoord', 'secret':'secret', 'verbose': False,}
elif(sys.argv[1]=="193.191.187.41"):
	cisco_5505 = {'device_type':'cisco_asa', 'ip':'193.191.187.41', 'username':'ucll', 'password': 'ucllwachtwoord', 'secret':'secret', 'verbose': False,}
#cisco asa instellingen
#TODO rsa key of username en password in andere file

net_connect = ConnectHandler(**cisco_5505) #ssh connection opstarten met de cisco_5505

net_connect.enable() # enable commando
net_connect.send_command('\n') #enable password 
net_connect.send_command('conf t')# execute commando
command = ' '.join( sys.argv[2:])
output=net_connect.send_command(command) #all except first
newoutput = output.replace("-", "")


print "<alles>",newoutput,"</alles>"
