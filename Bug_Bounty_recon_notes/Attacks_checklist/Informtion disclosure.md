
| **Example** | **Why It’s Dangerous** |
| --- | --- |
| `robots.txt` file listing hidden paths | Attackers can check what's being hidden and why |
| Error message: `Column 'password_hash' not found` | Tells attacker the app is using a `password_hash` column |
| Source code left in `.bak` or `.old` backup files | Can reveal credentials, logic, or API keys |
| Exposing internal IPs or service versions | Helps attackers map internal networks or exploit known bugs |
Directory Listings
- Temporary files
- Logs
- Crash dumps
- Backup zips

3. 🧑🏽‍💻 Developer Comments in Code
4.  ⚠️ Error Messages
- "SQL error near `SELECT * FROM users`..." ➡️ Reveals it's using SQL, and even shows part of a query.
- "Template not found: user_dashboard.html" ➡️ Shows what template engine it’s using.
- "File not found: C:\xampp\htdocs\admin\config.php" ➡️ Leaks server file paths or OS.

🎯 Even the **difference between two error messages** can help:

- “Username not found” vs “Incorrect password” ➡️ Lets you know if a username exis

| 🔍 What You See | 💣 Why It’s Dangerous |
| --- | --- |
| Session variable values | Attackers might be able to change these to gain access or impersonate users |
| Server file paths | Can help attackers find sensitive files (like `/var/www/admin/config.php`) |
| Database hostnames and credentials | Gives direct access to back-end systems |
| Encryption keys | Can allow attackers to read or tamper with "secure" data |

## User Account Pages — How They Can Leak Info

User account or profile pages are supposed to be **private**, right? They usually show things like:

- Email address
- Phone number
- API keys
- Billing info, etc.

What Is Source Code Disclosure via Backup Files?
- `index.php~`
- `login.php.bak`
- `config.old`
- `admin.php.save`

The HTTP TRACE Method
That means the server echoes back everything you sent — including any secret headers.

.git Folder
```bash
/index.html
/style.css
/.git/
```

```bash
/.git/HEAD
/.git/config
/.git/logs/HEAD
/.git/objects/...
```

- **GitTools (GitDumper)** – to download the full `.git` folder
- **git log**, **git show**, **git diff** – to view history and changes


proper checklist :
```bash
curl https://example.com/robots.txt
curl https://example.com/sitemap.xml
curl https://example.com/.env
curl https://example.com/.git/config
curl https://example.com/.DS_Store
```


```bash
gobuster dir -u https://example.com -w /usr/share/wordlists/dirb/common.txt -x php,html,txt,bak,old
```


```bash
git clone https://github.com/internetwache/GitTools.git
cd GitTools/Dumper
./gitdumper.sh https://example.com/.git/ /tmp/site-git/
cd /tmp/site-git/
git log
```


```bash
curl -X TRACE -i https://example.com
```


Use Burp Intruder to send fuzz payloads:

- Target all input fields, cookies, headers
- Monitor for:
    - Error messages (`500`, `Stack trace`, `SQL error`)
    - Keyword matches: `SELECT`, `NullReference`, `password`, `debug`, `traceback`



1. View page source (Ctrl+U) or use DevTools:
```bash
<!-- TODO: remove debug flag -->
<!-- admin page at /hidden/admin -->
```

2. Check for backup and temporary files:
```bash
   .php~   .bak   .old   .save   .swp   .zip
```


automated tools
```bash
nikto -h https://example.com
nuclei -u https://example.com -t exposures/
nuclei -u https://target.com -t exposures/ -v

python3 dirsearch.py -u https://target.com -e php,txt,bak,old,zip



./gitdumper.sh https://target.com/.git/ /tmp/target-git/
cd /tmp/target-git/
git log


whatweb https://target.com
```


- Frameworks (Laravel, Express)
- Web servers (Apache, Nginx)
- Languages (PHP, Ruby, etc.)

```bash
./xray webscan --url https://target.com --html-output report.html
```


```bash
git clone https://github.com/danielmiessler/SecLists.git
```

- `SecLists/Discovery/Web-Content/`
- `SecLists/Fuzzing/`
- `SecLists/Miscellaneous/`

```bash
https://github.com/streaak/keyhacks
```