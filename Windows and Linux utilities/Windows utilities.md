 

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

How to remove any kind of dual boot ?

```bash
login to host operating system
delete the partition of dual boot os then extend to c drive

then open cmd run as administrator delete ubuntu or other os bootloader
bcdedit /set {bootmgr} path \EFI\Microsoft\Boot\bootmgfw.efi
bcdedit /enum firmware
bcdedit /delete (identifier no)

after
diskpart
list disk
select disk 0
list vol
select volume 2 (recovery partition)
assign letter=Z:
exit
Z:
cd efi
dir
rmdir /s ubuntu
mountvol Z: /d
```

How to remove Bitlocker :

```bash
Lauch cmd and type notepad  open file explorer look for recovery key file

https://aka.ms./myrecoverykey

manage-bde -status
Disable-BitLocker -MountPoint "F:"
manage-bde -off F:
```


How to solve c drive issue:

```bash
c drive full

Filter 
size:gigantic
size:huge


Delete window.old folder in c drive 
C:/windows/softwaredistribution

In run type %appdata% go the local delete removed software folder

Diskcleanup

Use storage sense

Go to chrome the userdata folder and look for ai model something folder





Use automation
  Common
  Delete user from pc
  Run cleanup 
Run tree icon
Adjust settings for performance check


Service should disable:
Connected user experience and telemetry
Windows error reporting service
Diagnostic policy service
Progeam compatibility assistance service
Windows biometric service
Windows search
Bitlocker encryption drive
Bluethooth support service
Remote desktop support
SysMain
Printspoller

System configuration -》services -》 hide microsoft services -》 disable such service

Disable start up application 
Go to the power option disable hibranate
In powershell
  powercfg /hibernate off
 powercfg /availablesleepstates
```



use full software and script :
```bash
Everything
Windows apps must
Widget store
Remove windows 11 ai
Tokri
Wintoy
Revo uninstallaion
Windhawk
Bilp for data transfer
Powertoys
```



17 RUN Tools Windows Commands:
```bash
resmon       : Resource monitor
explorer     : Open file explorer
cleanmgr     : disk clean up
winver       : windows version os
msconfig     : system configration
msinfo32     : System information
main.cpl     : mouse properties
mstsc        : open remote desktop connection
taskmgr      : open task manager
rstrui.exer  : system restore option
gpedit.msc   : open local group policy manager
regedit      : registry editor
magnify      : open magnify
powercfg.cpl : open poweroption
ncpa.cpl     : open networking setting
mrt          : malicious software removal
appwiz.cpl   : program and feature

```








