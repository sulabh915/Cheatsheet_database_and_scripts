


Finding Login Panels
```bash
site:*<*.target.com intext:"login" | intitle:"login" | inurl:"login" | intext:"username" | intitle:"username" | inurl:"username" | intext:"password" | intitle:"password" | inurl:"password"
```

Finding API docs
```bash
inurl:apidocs | inurl:api-docs | inurl:swagger | inurl:api-explorer site:"target.com"
```

Finding API Endpoints
```bash
 site:target.com inurl:api | site:*/rest | site:*/v1 | site:*/v2 | site:*/v3
```

Third Party Dorking(check where developer write code in third party platform):
```bash
site:http://ideone.com | site:http://codebeautify.org | site:http://codeshare.io | site:http://codepen.io | site:http://repl.it | site:http://justpaste.it | site:http://pastebin.com | site:http://jsfiddle.net | site:http://trello.com | site:*.atlassian.net | site:bitbucket.org "target.com"

```

information discosure:
```bash
site:example[.]com ext:log | ext:txt | ext:conf | ext:cnf | ext:ini | ext:env | ext:sh | ext:bak | ext:backup | ext:swp | ext:old | ext:~ | ext:git | ext:svn | ext:htpasswd | ext:htaccess | ext:json
```


```bash

### 🛠 Step 1: Combining Basic Operators

Example:

inurl:admin intitle:login site:gov

➡️ Finds government websites with "admin" in the URL and "login" in the page title.

Common Basics:

* site: → Limit to domain/TLD
* intitle: → Search in page titles
* inurl: → Search in URLs
* filetype: → Filter by file format

---

### 🌐 Step 2: Using Language Filters

Example:

site:.gov "sensitive data" filetype:pdf lr:lang_es

➡️ Finds Spanish-language PDFs on .gov sites.

---

### 🏳️ Step 3: Region-Specific Searches

Example:

intitle:"index of" "password" site:.gov filetype:xlsx cr:US

➡️ Finds Excel files on U.S. government sites mentioning “password”.

---

### 📂 Step 4: Advanced Filetype Searches

Rare & valuable filetypes:

* sql → Database dumps
* ini → Configs (with credentials)
* bak → Backups
* log → Log files
* json → API keys & user data

Example:

filetype:sql "create table" inurl:backup

➡️ Finds exposed database backups.

---

### 🔑 Step 5: Exposed Login Portals

Example:

inurl:admin intitle:login site:edu

➡️ Finds exposed admin logins on university sites.

---

### 📊 Step 6: Publicly Exposed Documents

Example:

site:.gov filetype:xlsx "sensitive"

➡️ Finds spreadsheets containing “sensitive” information.

---

### 📹 Step 7: Security Cameras & IoT Devices

Example:

intitle:"webcamXP 5" inurl:8080

➡️ Finds open webcams 🌍.

---

### 📁 Step 8: Hidden Directories & Indexes

Example:

intitle:"index of" "parent directory" inurl:ftp filetype:log

➡️ Finds exposed FTP directories with log files.

```





https://pentest-tools.com/information-gathering/google-hacking
https://www.exploit-db.com/google-hacking-database
https://taksec.github.io/google-dorks-bug-bounty/
https://github.com/BullsEye0/dorks-eye/tree/master
https://github.com/Proviesec/google-dorks
https://dorksearch.com/
https://dorks.faisalahmed.me/#
https://github.com/SecShiv/OneDorkForAll/tree/main/dorks/1M_dork
https://github.com/lynx-family/lynx


BugBountyRecon.exe