
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
