#!/usr/bin/python
import sys
import socket
try:
	print socket.gethostbyaddr(sys.argv[1])[0]
except socket.herror:
	print 0
