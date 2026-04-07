
#### how to create virtual host 

```bash
sudo nano /etc/hosts

#add
127.0.0.1 app1.local
127.0.0.1 app2.local
```


##### Doing in apache2 virtual host
```bash
sudo apt update
sudo apt-get install apache2 -y

#create directories
sudo mkdir -p /var/www/app1
sudo mkdir -p /var/www/app2

#add sample pages
echo "<h1>App1 Website</h1>" | sudo tee /var/www/app1/index.html
echo "<h1>App2 Website</h1>" | sudo tee /var/www/app2/index.html

#add permission
sudo chown -R $USER:$USER /var/www/app1
sudo chown -R $USER:$USER /var/www/app2


#create virtual host
sudo nano /etc/apache2/sites-available/app1.conf

<VirtualHost *:80>
    ServerName app1.local
    DocumentRoot /var/www/app1

    ErrorLog ${APACHE_LOG_DIR}/app1_error.log
    CustomLog ${APACHE_LOG_DIR}/app1_access.log combined
</VirtualHost>


sudo nano /etc/apache2/sites-available/app2.conf

<VirtualHost *:80>
    ServerName app2.local
    DocumentRoot /var/www/app2

    ErrorLog ${APACHE_LOG_DIR}/app2_error.log
    CustomLog ${APACHE_LOG_DIR}/app2_access.log combined
</VirtualHost>


sudo a2ensite app1.conf
sudo a2ensite app2.conf


sudo systemctl restart apache2



```

##### Nginx virtual host (server blocks)
```bash
sudo apt install nginx -y

sudo nano /etc/nginx/sites-available/app1
server {
    listen 80;
    server_name app1.local;

    root /var/www/app1;
    index index.html;

    location / {
        try_files $uri $uri/ =404;
    }
}

sudo nano /etc/nginx/sites-available/app2
server {
    listen 80;
    server_name app2.local;

    root /var/www/app2;
    index index.html;

    location / {
        try_files $uri $uri/ =404;
    }
}

#Enable sites (symbolic links)
sudo ln -s /etc/nginx/sites-available/app1 /etc/nginx/sites-enabled/
sudo ln -s /etc/nginx/sites-available/app2 /etc/nginx/sites-enabled/

#Test config
sudo nginx -t

#restart nginx
sudo systemctl restart nginx
```

Host header attack = attacker changes the “website name” in the request to trick the server


```bash
Even if you visit:

https://example.com

An attacker can send:\

GET / HTTP/1.1
Host: evil.com

Request still goes to example.com server, but server thinks it's for evil.com.
```


#### Step-by-Step Real Attack (Password Reset)

```bash
User clicks “Forgot Password”

Server sends email:

https://example.com/reset?token=abc123

Internally:

$link = "https://" . $_SERVER['HOST'] . "/reset?token=abc123";


Using Burp Suite

They modify:

POST /forgot-password HTTP/1.1
Host: evil.com

Step 3: Server trusts it

Server generates:

https://evil.com/reset?token=abc123

Step 4: Victim clicks
Victim receives email
Clicks link → goes to attacker site
Attacker steals token

👉 Account takeover
```

### Web Cache Poisoning
```bash
Attacker sends:

GET / HTTP/1.1
Host: evil.com

Server response:

<img src="https://evil.com/logo.png">

If cached:

👉 Every user now loads attacker content
```

### SSRF (Internal Access)
```bash
Attacker sends:

GET / HTTP/1.1
Host: internal.company.local

If routing is based on Host:

👉 Server may access internal systems
```

### Host Header Injection in URLs
```bash
Server generates links like:

https://HOST/profile

Attacker changes:

Host: attacker.com

👉 All links become attacker-controlled
```

### Using Override Headers
```bash
Host: example.com
X-Forwarded-Host: evil.com

👉 Some servers trust X-Forwarded-Host instead
```


#### How  Test This (VERY IMPORTANT)
```bash
Step 1: Send random Host
GET / HTTP/1.1
Host: random123.com

👉 If site still works:

🚨 Vulnerable behavior possible

✅ Step 2: Observe response

Check:

Links
Redirects
Emails
Headers

✅ Step 3: Try bypass tricks

🔁 Trick 1: Add port
Host: example.com:evil

👉 Some systems ignore port → bypass validation

🔁 Trick 2: Subdomain bypass
Host: attacker-example.com

👉 Weak validation may accept it

🔁 Trick 3: Duplicate Host headers
Host: example.com
Host: evil.com

👉 Frontend uses first, backend uses second

🔁 Trick 4: Absolute URL
GET https://example.com/ HTTP/1.1
Host: evil.com

👉 Confuses routing logic

🔁 Trick 5: Header wrapping
 Host: evil.com
Host: example.com

👉 Different servers interpret differently

🏗️ 5. Why This Happens (Real Architecture)

Modern apps are complex:

User → CDN → Load Balancer → App Server

Problems:

Frontend validates Host
Backend uses Host blindly

👉 mismatch = vulnerability

Also:

Devs assume Host is safe ❌
Third-party tools auto-enable headers ❌
Misconfigurations everywhere
```


```bash
How to Fix (What Good Looks Like)
✅ 1. Whitelist domains
Allowed:
- example.com
- www.example.com

Reject everything else.

✅ 2. Don’t use Host header for critical logic

❌ Bad:

$_SERVER['HOST']

✅ Good:

BASE_URL = "https://example.com"
✅ 3. Validate override headers
X-Forwarded-Host
X-Host-Forwarded

✅ 4. Proper proxy configuration

Ensure:

Frontend and backend agree on Host
No ambiguity
```


#### HTML Injection via Host Header

```bash
HTML Injection via Host Header

Even if you can’t change the reset link, attacker may still inject HTML.

Example

Server sends email:

<a href="https://HOST/reset">Reset</a>

Attacker sends:

Host: evil.com"><img src=x onerror=alert(1)>

Email becomes:

<a href="https://evil.com"><img src=x onerror=alert(1)>/reset">

Impact
Broken email UI
Phishing tricks
Data leakage (dangling markup attacks)

👉 Note: JavaScript usually blocked in email, but HTML tricks still work
```