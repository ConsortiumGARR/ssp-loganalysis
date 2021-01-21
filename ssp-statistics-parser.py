'''
   Run the script where the "simplesamlphp.log" file is saved.

   Usage:
   
   Tested with Python v2.7.16:
   python ssp-statistics-parser.py > /var/log/simplesamlphp.stat
   
   Tested with Python v3.7.3:
   python3 ssp-statistics-parser.py > /var/log/simplesamlphp.stat
'''

import socket
import sys

with open('simplesamlphp.log','r') as f:
   for line in f:
       if "STAT" in line:
          l = line.replace("simplesamlphp NOTICE","%s simplesamlphp: 5" % socket.gethostname())
          print(l.replace('\n',''))
