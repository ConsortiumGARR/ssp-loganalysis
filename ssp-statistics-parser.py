import socket
import sys

with open('simplesamlphp.log','r') as f:
   for line in f:
       if "STAT" in line:
          l = line.replace("simplesamlphp NOTICE","%s simplesamlphp: 5" % socket.gethostname())
          print(l.replace('\n',''))
