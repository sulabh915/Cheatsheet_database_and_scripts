
GitHub Dorks for Finding Files:
```bash
filename:manifest.xml
filename:travis.yml
filename:vim_settings.xml
filename:database
filename:prod.exs NOT prod.secret.exs
filename:prod.secret.exs
filename:.npmrc _auth
filename:.dockercfg auth
filename:WebServers.xml
filename:.bash_history <Domain name>
filename:sftp-config.json
filename:sftp.json path:.vscode
filename:secrets.yml password
filename:.esmtprc password
filename:passwd path:etc
filename:dbeaver-data-sources.xml
path:sites databases password
filename:config.php dbpasswd
filename:prod.secret.exs
filename:configuration.php JConfig password
filename:.sh_history
shodan_api_key language:python
filename:shadow path:etc
JEKYLL_GITHUB_TOKEN
filename:proftpdpasswd
filename:.pgpass
filename:idea14.key
filename:hub oauth_token
HEROKU_API_KEY language:json
HEROKU_API_KEY language:shell
SF_USERNAME salesforce
filename:.bash_profile aws
extension:json api.forecast.io
filename:.env MAIL_HOST=smtp.gmail.com
filename:wp-config.php
extension:sql mysql dump
filename:credentials aws_access_key_id
filename:id_rsa or filename:id_dsa
```

GitHub Dorks for Finding Languages

```bash
language:python username
language:php username
language:sql username
language:html password
language:perl password
language:shell username
language:java api
HOMEBREW_GITHUB_API_TOKEN language:shell
```

GiHub Dorks for Finding API Keys, Tokens and Passwords

```bash
api_key
“api keys”
authorization_bearer:
oauth
auth
authentication
client_secret
api_token:
“api token”
client_id
password
user_password
user_pass
passcode
client_secret
secret
password hash
OTP
user auth
```

GitHub Dorks for Finding Usernames:

```bash
user:name (user:admin)
org:name (org:google type:users)
in:login (<username> in:login)
in:name (<username> in:name)
fullname:firstname lastname (fullname:<name> <surname>)
in:email (data in:email)
```

GitHub Dorks for Finding Information using Dates

```bash
created:<2012–04–05
created:>=2011–06–12
created:2016–02–07 location:iceland
created:2011–04–06..2013–01–14 <user> in:username
```

GitHub Dorks for Finding Information using Extension:

```bash
extension:pem private
extension:ppk private
extension:sql mysql dump
extension:sql mysql dump password
extension:json api.forecast.io
extension:json mongolab.com
extension:yaml mongolab.com
[WFClient] Password= extension:ica
extension:avastlic “support.avast.com”
extension:json googleusercontent client_secret
```


GitHub dorking for finding GitHub Personal Access Tokens:

```bash
filename:.env "ghp_" 
path:.github/workflows "github_token" extension:yml  
language:javascript "access_token"  
extension:json "API_KEY"  
filename:.properties "AUTH_TOKEN"  
filename:.env "password" OR "token"  
filename:.config "token"  
extension:json "secret"  
extension:yaml "password"  
filename:.git-credentials  
language:shell "export TOKEN="  

```






Important keyword :
```bah
"aws_access_keyl
aws_secret_key
| api key
| passwd |
pwd
|heroku| slack | firebase | swagger | aws_secret_key | aws key | password |ftp
password|jdbc |db|sq1|secret jet| config|admin|pwd|json|gcp|htaccess|.env|ssh
key| .git| access key| secret token| oauth_token| oauth_token_secret"
```

| **Dorking Operator** | **Description** |
| --- | --- |
| `site:github.com` | Searches only within GitHub. |
| `filename:<filename>` | Finds specific files (e.g., `filename:.env`). |
| `extension:<file-extension>` | Searches for specific file types (e.g., `extension:json`). |
| `org:<organization-name>` | Finds repositories under a specific GitHub organization. |
| `repo:<repository-name>` | Searches within a specific repository. |
| `"keyword"` | Finds exact matches for a keyword. |
| `api_key` | Searches for exposed API keys. |
| `password` | Finds occurrences of the word “password” in code. |
| `secret` | Searches for secrets in public repositories. |
| `AWS_ACCESS_KEY_ID` | Looks for exposed AWS credentials. |
| `"BEGIN RSA PRIVATE KEY"` | Finds private SSH keys. 🚨 High risk! |
| `filename:.env` | Searches for `.env` files (may contain secrets). |
| `filename:.git-credentials` | Looks for Git authentication credentials. |
| `filename:id_rsa` | Finds SSH private keys. 🚨 High risk! |
| `filename:config` | Searches for generic config files. |
| `filename:credentials extension:ini` | Finds credentials stored in `.ini` files. |
| `DB_PASSWORD` | Searches for database passwords in code. |
| `"mongodb://"` | Looks for exposed MongoDB connection strings. |
| `INSERT INTO users` | Searches for SQL statements with user data. |
| `filename:*.pem` | Finds private key files with `.pem` extension. |
| `filename:*.env` | Looks for environment variable files.se |



if we found any sensitive information like api keys used this github repo for verify the leaked api keys.
https://github.com/streaak/keyhacks?tab=readme-ov-file