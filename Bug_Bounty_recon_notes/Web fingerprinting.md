

Used whatweb web site or command line tool:
[Whatweb](https://whatweb.net/)

Used Wappalyzer extension

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