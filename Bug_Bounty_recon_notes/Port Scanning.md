
#### Collect IP address block using asn:
https://asnlookup.com/
https://bgp.he.net/
https://mxtoolbox.com/SuperTool.aspx#

search using asnmap:
```bash
asnmap -d domain.com
asnmap -a <asn number>
asnmap -org GOOGLE
```

```bash
echo 17.0.0.0/18 | mapcidr -slient | dnsx -ptr -resp-only 
```

getting all possible live ip address:
```bash
asnmap -a AS134027 -silent | sort -u | mapcidr -silent > all_ip.txt

#Used angry ip scanner for check live hosts.

nmap -iL all_ips.txt -sn -n -T4 -oG - | grep "Up" | cut -d " " -f2 > live_ips.txt

```

using shodan cli:
```bash
shodan download asn134027.json.gz "asn:AS134027"
shodan parse --fields ip_str --separator , asn134027.json.gz > ips_asn134027.txt
```

search in shodan run this javascript:
```bash
const ips = Array.from(document.querySelectorAll('a[href^="/host/"].title.text-dark'))
  .map(a => a.textContent.trim());
console.log("Extracted IPs:", ips);
console.log("Copy-pasteable list:\n" + ips.join("\n"));
```


search in censys run this javascript:
search this in censys : "host.autonomous_system.asn="134027""
```bash
const ips = Array.from(document.querySelectorAll('div.apoUv'))
  .map(div => div.textContent.trim());
console.log("Extracted IPs:", ips);
console.log("Copy-pasteable list:\n" + ips.join("\n"));
```

ssl.cert.subject.cn:"ferrero.com"
hostname:"*.example.com"

or search other search engine like above .


Identify the alive ip address :
- use angry ip scanner 
- use nmap

now port scan .....

using masscan:
```bash
masscan 192.168.1.0/24 -p80
masscan 10.0.0.0/8 -p80,443,8080
masscan 192.168.1.0/24 -p22 -oG ssh_hosts.txt
masscan 192.168.1.0/24 -p80 --rate=1000
masscan -iL ips.txt -p80
masscan 192.168.0.0/16 -p80 --excludefile exclude.txt
masscan -iL ips.txt -p80 -oG - | grep 'open' | awk '{print $2}' | httpx -silent

masscan <target> -p 21,22,23,25,53,80,110,111,135,139,143,443,445,993,995,1723,3306,3389,5900,8080
--http-user-agent "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko/20100101 Firefox/67.0" --rate 100000 --oL "output.txt"

masscan -p1-65535 --rate 10000 -oL masscan_results. txt target.com'


masscan 10.1.1.1/24 -p 0-65535 --rate 1000000 --open-only --http-user-agent \
"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:67.0) Gecko/20100101 Firefox/67.0"\
 -oL "output.txt"
```

using naabu:
```bash
naabu -host 192.168.1.0/24
naabu -iL targets.txt
naabu -p - -iL targets.txt
naabu -p 80,443,8080,8443 -iL hosts.txt
naabu -iL hosts.txt -exclude cdn.txt
naabu -host 10.10.0.0/16 -rate 10000
naabu -iL hosts.txt -silent
naabu -iL targets.txt -o ports.txt
naabu -list domains.txt -verify -resolvers resolvers.txt
naabu -iL live_hosts.txt -p 80,443,8000,8080,8443 -rate 10000 -o open_ports.txt -silent
echo hackerone.com | naabu -nmap-cli 'nmap -sV -oX nmap-output'

```


using search engine for port scan:
```bash
shodan:
hostname:"example.com" port:21,22,23,80,443,8080,8443,3306,6379,27017
ssl.cert.subject.cn:"example.com"

censys:
services.service_name:HTTP AND services.tls.certificates.leaf_data.subject.common_name: "example.com"
services.port:22 AND location.country: "United States"

zoomeye:
site:"example.com"
site:"example.com" port:22

Binaryedge:
domain:example.com
domain:example.com port:80
```


Scan subdomain ports:
```bash
naabu -iL fina_sub.txt -p 80,443,8080,8443,22,21,3306,5432,6379,27017 \
-rate 1000 -verify -silent -o open_ports.txt

naabu -iL live_sub.txt -p 80,443,8080,8443 -nmap-cli 'nmap -sV -Pn -T4' -o naabu_nmap.txt

naabu -iL live_sub.txt -top-ports 100 -rate 2000 -verify -silent -o open_ports.txt
cat open_ports.txt | httpx -silent -o alive_ports.txt

dnsx -l live_sub.txt -o resolved_ips.txt -silent
masscan -p1-65535 -iL resolved_ips.txt --rate 10000 -oG masscan_output.gnmap

nmap -iL live_sub.txt -Pn -p 80,443,8080,8443,22 --open -T4 -oN nmap_ports.txt

#for only http and https server.
httpx -l live_sub.txt -p 80,443,8000,8080,8443 -silent -o http_alive.txt

```