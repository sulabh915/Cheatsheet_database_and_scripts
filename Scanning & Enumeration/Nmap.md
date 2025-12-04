
### Syntax :
```bash
Nmap -n -sT <ip or ip range> -p22,23,80 --reason
```

### Host Discovery
```bash
sudo nmap 10.129.2.0/24 -sn -oA tnet | grep for | cut -d" " -f5
sudo nmap -sn -oA tnet -iL hosts.lst | grep for | cut -d" " -f5 #input file
sudo nmap -sn -oA tnet 10.129.2.18 10.129.2.19 10.129.2.20| grep for | cut -d" " -f5
sudo nmap -sn -oA tnet 10.129.2.18-20| grep for | cut -d" " -f5
sudo nmap 10.129.2.18 -sn -oA host
sudo nmap 10.129.2.18 -sn -oA host -PE --packet-trace #icmp echo request with packet trace

sudo nmap 10.129.2.18 -sn -oA host -PE --reason  #checkout the reason
sudo nmap 10.129.2.18 -sn -oA host -PE --packet-trace --disable-arp-ping #disable arp ping 

nmap -sP -PR 192.168.1.1/24 # -sn = -sP enable arp ping scan
nmap -PE -sn 192.168.178.0/24 #icmp echo request....
```


> [!NOTE] Remember:
> Here “-sn” is the ping scan mean no port scan also “-n” is to avoid dns name resolution
> 

### TCP flags :
```bash
TCP flags:

SYN (Synchronize): Initiates a connection.
ACK (Acknowledgment): Confirms received data or acknowledges a connection.
FIN (Finish): Indicates the end of data transmission.
RST (Reset): Resets the connection.
PSH (Push): Requests immediate data delivery to the application.
URG (Urgent): Indicates the presence of urgent data in the packet.
```

### Port states :

![[Pasted image 20251203002231.png]]

### Syn scan :
```bash
nmap -sS <ipaddress> --top-ports 50  #for specific port -p(port number,port ranges)
```

### Ports input :
```bash
nmap -sS <ip address> -p22,80,100-200
nmap -sS -sU <ip block> -pT:80,443,U:53,139-150
nmap -sS <ip block> --top-ports 20
nmap -sS <ip block> -F  #top 1000 ports
nmap -sS <ip block> -p1-65535
nmap -sS --top-ports 10 --open 172.20.1.0/24 -Pn -n
```

### Ports scan :
```bash
nmap -sT -n -Pn <ip block> (port paramter) #TCP scan
nmap -n -Pn -sU <ip block> --top-ports 10 -sV --reason #UDP scan
nmap -n -Pn -sS <ip block> --top-ports 10 -sV #version scan
nmap -n -sS <ip block> --top-ports 100 -o --ossc  #OS detction 
nmap -sV --script=banner -p21 10.10.10.0/24
sudo nmap 10.129.2.28 -p- -sV --stats-every=5s
sudo nmap 10.129.2.28 -p- -sV -Pn -n --disable-arp-ping --packet-trace
```


### Nmap input & Output Management:
```bash
nmap -sn -n <ip block> | grep "Nmap scan" |cut -d" " -f5 > ipList.txt

Normal output (-oN) with the .nmap file extension
Grepable output (-oG) with the .gnmap file extension
XML output (-oX) with the .xml file extension

sudo nmap 10.129.2.28 -p- -oA target #save result in all format

xsltproc target.xml -o target.html #convert .xml to .html
```

### Nmap Aggressive Scan:
```bash
Nmap Aggressive Scan with -A Parameter
Beyond the parameters mentioned, Nmap has a very useful parameter: **-A**.
This option enables additional advanced and aggressive options. Presently this enables

- OS detection (-O),
- Version scanning (-sV),
- Script scanning (-sC) and
- Traceroute
```

### Some other scan nmap:
```bash
NULL scan(-sN)
• NO flag is set

FIN Scan(-sF)
• Only the FIN flag is set

Xmas Scan(-sX):
• FIN,PSH and URG flags are set

ACK Scan(-sA)
Send a packet with ACK flag
Cannot know if the port is open or closed
To detect if there is a filtering

Idel(Zombie) scan:
Idle Scan(-sI)
Truly blind TCP port scan
NO packets from you
Zombie host to gather information
Facts behind the idle Scan:
SYN is responded with SYN/ACK if the port is open,RST if it's closed
Unexpected SYN/Ack is responded with a RST
Every IP packet has IP ID,which is simply incremented by many 1s.

sudo nmap -sI 192.168.0.107 -Pn -n 192.168.0.104 --top-ports 3

```

### Firewall evasion:
```bash
#Fin packet :
nmap -sF -p80 nmap.scanme.org

#Ack packet :
nmap -sA -p80 nmap.scanme.org

#Null packet :
nmap -sN -p80 nmap.scanme.org

#Decoys :
nmap -sS -sV -F -D RND:3 nmap.scanme.org

#change the source port to legitmate traffic 53
nmap --source-port 53 -p80 192.168.0.104 -sS

#change the mac addresss
nmap --spoof-mac 00:11:22:33:44:55 -p80 192.168.0.104

#packet fragment
nmap -sS -sV -F -f --send-eth -D RND:2 nmap.scanme.org
sudo nmap 10.129.2.28 -n -Pn -p 445 -O -S 10.129.2.200 -e tun0

#change the mtu size of packet
nmap -sS -sV -F -mtu 16 --send-eth -D RND:2 nmap.scanme.org

#Directly connected filter port.
ncat -nv --source-port 53 10.129.2.28 50000
```


### Timing and performace :
-T 0 / -T paranoid
-T 1 / -T sneaky
-T 2 / -T polite
-T 3 / -T normal
-T 4 / -T aggressive
-T 5 / -T insane

how many packet Nmap sends at the same time.
```bash
nmap -sS -sV  -p22-220 --min-parallelism 5 nmap.scanme.org
#–max-parallelism

```

The host group size allows you to specify how many hosts to scan simultaneously.
–min-hostgroup –minimum
–max-hostgroup –maximum (Great when working with restraints)
```bash
nmap -sS -p21-443 --min-hostgroup 10 192.168.1.1/24
```

host timeout :
```bash
nmap -Pn -p- 192.168.1-255.1-255 --host-timeout 30s
```

delay the scan :
```bash
 nmap -sT --scan-delay 10s nmap.scanme.org
```

you can specify the minimum and maximum amount of packets you want to send per second.
```bash
nmap -sT --min-rate 20 nmap.scanme.org
nmap -sT --max-rate 50 scanme.nmap.org
```

### Aditional options :
```bash
--reason explains how nmap made its conclusion
-v       verbose
-vv      very verbose
-d       debugging
-dd      more details for debugging
```

### Script Scanning:
- Lua programming language
- sC or --script , syntax :nmap -p21 --script scriptname1,sciptname2,expressionused ip-block
- Nmap --script-updatedb
- /usr/share/nmap/scripts
- locate *.nse | grep ssh
- Tasks you can do with NSE
       Network discovery
       Banner grabbing :
  - More sophisticated version detection
   - Vulnerability detection
   - Backdoor detection
   - Vulnerability exploitation
   - nmap -sS -p23 ip_address —script telnet-brute
   - nmap -sU -p53 ip_address —script “dns-*”

```bash
auth	    Determination of authentication credentials.
broadcast	Scripts, which are used for host discovery by broadcasting and the discovered hosts, can be automatically added to the remaining scans.
brute	    Executes scripts that try to log in to the respective service by brute-forcing with credentials.
default	    Default scripts executed by using the -sC option.
discovery	Evaluation of accessible services.
dos	        These scripts are used to check services for denial of service vulnerabilities and are used less as it harms the services.
exploit	    This category of scripts tries to exploit known vulnerabilities for the scanned port.
external	Scripts that use external services for further processing.
fuzzer	    This uses scripts to identify vulnerabilities and unexpected packet handling by sending different fields, which can take much time.
intrusive	Intrusive scripts that could negatively affect the target system.
malware	    Checks if some malware infects the target system.
safe	    Defensive scripts that do not perform intrusive and destructive access.
version	    Extension for service detection.
vuln	    Identification of specific vulnerabilities.
```

```bash
sudo nmap 10.129.2.28 -p 25 --script banner,smtp-commands
```

## Enumeration 

### FTP Enumeration :
```bash
sudo nmap -p21 --script ftp-anon,ftp-syst,tftp-enum,ftp-vsftpd-backdoor 192.168.0.104

#brute force
nmap --script ftp-brute -p21 192.168.43.181 --script-args userdb=users.txt,passdb=passwords.txt
```

### DNS enumeration:
```bash
nmap --script dns-zone-transfer --script-args dns-zone-transfer.server=nsztml.digi.ninja,dns-zone-transfer.port=53,dns-zone-transfer.domain=zonetransfer.me
```


### HTTP enumeration , Detecting HTTP methods:
```bash
sudo nmap -sV -Pn -n -T4 --script http-methods scanme.nmap.org -p80

nmap -p 80 --script=http-form-brute --script-args userdb=/usr/share/legion/wordlists/ssh-user.txt,passdb=/usr/share/legion/wordlists/ssh-password.txt,http-form-brute.path=/dvwa/login.php 192.168.178.136

sudo nmap -sV -T4 -p80 scanme.nmap.org --script http-enum
```


### SMPT enumeration:
```bash
Nmap -p25 --script smtp-enum-users --script-args smtp-enum-users.methods={VRFY} <ipblock>
```

### SMB enumeration:
```bash
smb os dicovery
Smb
```

### SSH Enumeration & brute forcing :
```bash
nmap --script ssh-brute -p22 192.168.178.136 --script-args userdb=/usr/share/legion/wordlists/ssh-user.txt,passdb=/usr/share/legion/wordlists/ssh-password.txt
```


### Vulnerability scanning
```bash
nmap -sV -p21-8080 --script vulners <ipblock>
sudo nmap 10.129.2.28 -p 80 -sV --script vuln 
```