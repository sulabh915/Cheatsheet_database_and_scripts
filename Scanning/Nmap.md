
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
nmap -sT -n -Pn <ip block> (port paramter) 
nmap -n -Pn -sU <ip block> --top-ports 10 -sV --reason
nmap -n -Pn -sS <ip block> --top-ports 10 -sV #version scan
nmap -n -sS <ip block> --top-ports 100 -o --ossc  #OS detction 
nmap -sV --script=banner -p21 10.10.10.0/24

```


### Nmap input & Output Management:
```bash
nmap -sn -n <ip block> | grep "Nmap scan" |cut -d" " -f5 > ipList.txt
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

#change the mtu size of packet
nmap -sS -sV -F -mtu 16 --send-eth -D RND:2 nmap.scanme.org
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