

Sync your copy and paste :
```bash
robocopy "D:\MyBackup" "\\NetworkPath\Backup" /Z /J /R:3 /W:5
```


using smb service :
```bash
#**Shows all shared resources (folders/printers) on a computer.**
net view \\127.0.0.1

#Creates a share called Exfil that points to the given folder
net share Exfil=C:\Users\user\Download\exfil

#Lists all shares on the system.
net share

#Shows active sessions (who’s connected to your shares).
net session

#Maps a shared folder to a drive letter (X:).
net use X: \\127.0.0.1\Exfil

#Shows all mapped drives and active share connections.
net use
```

using DISM tool:
```bash
#backup driver 
DISM /Online /Export-Driver /Destination:D:\DriverBackup

#scan health 
DISM /Online /Cleanup-Image /ScanHealth

sfc /scannow
```

All in one Activation command 

```bash
irm https://get.activated.win | iex
iex (curl.exe -s --doh-url https://1.1.1.1/dns-query https://get.activated.win | Out-String)

https://github.com/massgravel/microsoft-activation-scripts
```

Extend C drive:

```bash
reagentc /disable

diskpart
list disk
select disk 0
select partition 4 #select reconvery partition

detail partition #save information Type,Attrib

delete partition override

now go the disk management -> extend C: drive

now shrink C: upto 1 gb and create volumn

go to the diskpar terminal

list partition

select partition 4

set id=(paste the type value)
set id=(paste the type value) override

gpt attributes=(paste the attributes value)

 list volume

select volume 4


remove letter=(whatever the letter is)

exit

reagentc /enable
```



