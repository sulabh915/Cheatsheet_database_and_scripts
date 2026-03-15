

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