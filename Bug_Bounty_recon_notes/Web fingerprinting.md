

Used whatweb web site or command line tool:
[Whatweb](https://whatweb.net/)

Used Wappalyzer extension

BuiltWith	Web technology profiler that provides detailed reports on a website's technology stack.	Offers both free and paid plans with varying levels of detail.

WhatWeb	Command-line tool for website fingerprinting.	Uses a vast database of signatures to identify various web technologies.

Nmap	Versatile network scanner that can be used for various reconnaissance tasks, including service and OS fingerprinting.	Can be used with scripts (NSE) to perform more specialised fingerprinting.

Netcraft	Offers a range of web security services, including website fingerprinting and security reporting.	Provides detailed reports on a website's technology, hosting provider, and security posture.

wafw00f	Command-line tool specifically designed for identifying Web Application Firewalls (WAFs).	Helps determine if a WAF is present and, if so, its type and configuration.


looking at server header 
```bash
Server: Apache/2.4.41 (Ubuntu)
Server: nginx/1.18.0
Server: Microsoft-IIS/10.0
```


Steps of measures to take for fingerprinting :
- Run nmap version scan.
- Check for response header , like PHPSESSID cookie header used by php or other.
- Check for html source code (look for framework been used).
- StackShare (https://stackshare.io/)  developers share there tech they are using.
- HTTP header (request & response) (X-Powered-By).
- Check for cookies.
- Specific files and folders.
- File extension.
- Error messages.

we can also identify the which web server is running by looking default 404 page not found page.

Sometimes, the default error pages or specific behavior can give away the web server. For example, the default 404 error page from Apache is different from that of Nginx.

[checkout404page](https://0xdf.gitlab.io/cheatsheets/404)

```bash
curl -I inlanefreight.com
curl -I inlanefreight.com
wafw00f inlanefreight.com
nikto -h inlanefreight.com -Tuning b
wpscan --url https://site.com --disable-tls-checks --api-token <here> -e at -e ap -e u --enumerate ap --plugins-detection aggressive --force
```
