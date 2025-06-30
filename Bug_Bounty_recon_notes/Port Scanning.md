
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


```