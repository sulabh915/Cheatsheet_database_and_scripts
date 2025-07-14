
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

Miscellaneous:
```bash
"example.com" password
org:example "password":

"domain" AND ("api_key" OR "secret" OR "password" OR "access_token" OR "client_secret" OR "private_key" OR "AWS_SECRET_ACCESS_KEY" OR "DB_PASSWORD" OR "slack_token" OR "github_token" OR "BEGIN RSA PRIVATE KEY")

filename:.env "DB_PASSWORD"
extension:json "access_token"

path:/config filename:database.php        # Finds database.php inside any /config directory
path:/wp-config.php                                # Targets the WordPress config file
path:/src/secrets                                     # Looks in typical config directories
path:/settings                                          # Looks in typical settings directories
path:/.ssh                                                # Searches hidden .ssh folder
path:/.git                                                 # Searches hidden .git folder
path:**/.env                                            # Finds .env files in any nested directory

repo:vercel/next.js filename:config.js

"domain" language:PHP password

try different varient of keyword :
password
passwd
pwd
pass



Authentication & Secrets:

api_key
access_token
client_secret
auth_token
authorizationToken
x-api-key
secret
SECRET_KEY
secret_token
credentials
token
secure


Cloud Provider Secrets

AWS_SECRET_ACCESS_KEY
AWS_ACCESS_KEY_ID
aws_access_key_id
aws_secret_key
aws_token
GCP_SECRET
gcloud_api_key
firebase_url
shodan_api_key

Database Credentials

DB_PASSWORD
DATABASE_URL
db_password
db_pass
MYSQL_PASSWORD
POSTGRES_PASSWORD
mongo_uri
mongodb_password


SSH & Private Keys

BEGIN RSA PRIVATE KEY
BEGIN OPENSSH PRIVATE KEY
BEGIN PGP PRIVATE KEY BLOCK
id_rsa
private_key
pem private
key

Service-Specific Tokens

slack_token
discord_token
github_token
gitlab_token
twilio_auth_token
mailgun
stripe_secret
SF_USERNAME salesforce



```


Automation :

using gitGraber:
```bash

python3 gitGraber.py -k wordlists/keywords.txt -q "nasa.gov" -s
python3 gitGraber.py -q "target.com"
python3 gitGraber.py -q "target.com" -k keywords.txt
python3 gitGraber.py -q "target.com" -l 7 #Recent Commits (less than 7 days old)
python3 gitGraber.py -q "target.com" -w wordlist.txt
python3 gitGraber.py -q "target.com" -m #run every 30 min
python3 gitGraber.py -q "target.com" -t 10


python3 gitGraber.py -q "target.com" -d  # Discord
python3 gitGraber.py -q "target.com" -s  # Slack
python3 gitGraber.py -q "target.com" -tg # Telegram

python3 gitGraber.py -q "target.com" -k keywords.txt -l 7 -w wordlist.txt -t 5

```


using truffleHog:
```bash
# Scan a public GitHub repository
trufflehog git https://github.com/username/repo.git

# Scan with filtering results to show only verified and unknown findings
trufflehog git https://github.com/trufflesecurity/test_keys --results=verified,unknown

# Scan and format output as JSON using jq for readability
trufflehog git https://github.com/trufflesecurity/test_keys --results=verified,unknown --json | jq

# Scan a GitHub repository and include issue and PR comments in the scan
trufflehog github --repo=https://github.com/trufflesecurity/test_keys --issue-comments --pr-comments

# Scan all repositories in a GitHub organization using a personal access token
trufflehog github --org=nasa --token=yourgithubtoken

# Scan a specific GitHub repository (basic usage)
	trufflehog github ---repo=https://github.com/username/repo


trufflehog s3 --bucket-name your-bucket-name
trufflehog docker --image my-docker-image:latest
trufflehog jenkins --url http://jenkins.target.com
trufflehog postman --collection=./collection.json
trufflehog gcs --bucket-name my-gcs-bucket
cat jsdump.txt | trufflehog stdin #Great for integrating with gau, waybackurls, etc.


gau target.com | grep '\.js' | httpx -mc 200 | tee js_urls.txt
cat js_urls.txt | while read url; do curl -s "$url"; done | trufflehog stdin --json



```

using nuclei :
```bash
cat domains.txt | nuclei -t gitExposed.yaml
```

find .git file  using GitTools, git-dumper or git-extractor:
```bash
httpx-toolkit -l subs.txt -path /.git/ -mc 200

cat domains.txt | httpx-toolkit -sc -server -cl -path "/.git/" -mc 200 -location -ms "Index of" -probe

cat domains.txt | grep "SUCCESS" | gf urls | httpx-toolkit -sc -server -cl -path "/.git/" -mc 200 -location -ms "Index of" -probe

git-dumper https://domain.com/.git/ outputdir

cd output_dir
git status
git restore .
git checkout .

```


using github-dork search with username :
```bash
github-dork.py -r techgaun/github-dorks                          # search a single repo
github-dork.py -u techgaun                                       # search all repos of a user
github-dork.py -u dev-nepal                                      # search all repos of an organization
GH_USER=techgaun GH_PWD=<mypass> github-dork.py -u dev-nepal     # search as authenticated user
GH_TOKEN=<github_token> github-dork.py -u dev-nepal              # search using auth token
GH_URL=https://github.example.com github-dork.py -u dev-nepal    # search a GitHub Enterprise instance
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

| **Dorking Operator**                 | **Description**                                            |
| ------------------------------------ | ---------------------------------------------------------- |
| `site:github.com`                    | Searches only within GitHub.                               |
| `filename:<filename>`                | Finds specific files (e.g., `filename:.env`).              |
| `extension:<file-extension>`         | Searches for specific file types (e.g., `extension:json`). |
| `org:<organization-name>`            | Finds repositories under a specific GitHub organization.   |
| `repo:<repository-name>`             | Searches within a specific repository.                     |
| `"keyword"`                          | Finds exact matches for a keyword.                         |
| `api_key`                            | Searches for exposed API keys.                             |
| `password`                           | Finds occurrences of the word “password” in code.          |
| `secret`                             | Searches for secrets in public repositories.               |
| `AWS_ACCESS_KEY_ID`                  | Looks for exposed AWS credentials.                         |
| `"BEGIN RSA PRIVATE KEY"`            | Finds private SSH keys. High risk!                         |
| `filename:.env`                      | Searches for `.env` files (may contain secrets).           |
| `filename:.git-credentials`          | Looks for Git authentication credentials.                  |
| `filename:id_rsa`                    | Finds SSH private keys. High risk!                         |
| `filename:config`                    | Searches for generic config files.                         |
| `filename:credentials extension:ini` | Finds credentials stored in `.ini` files.                  |
| `DB_PASSWORD`                        | Searches for database passwords in code.                   |
| `"mongodb://"`                       | Looks for exposed MongoDB connection strings.              |
| `INSERT INTO users`                  | Searches for SQL statements with user data.                |
| `filename:*.pem`                     | Finds private key files with `.pem` extension.             |
| `filename:*.env`                     | Looks for environment variable files.se                    |



if we found any sensitive information like api keys used this github repo for verify the leaked api keys.
https://github.com/streaak/keyhacks?tab=readme-ov-file



```bash
https://github.com/BishopFox/GitGot
https://github.com/Talkaboutcybersecurity/GitMonitor
https://github.com/michenriksen/gitrob
https://github.com/tillson/git-hound
https://github.com/kootenpv/gittyleaks
https://github.com/awslabs/git-secrets https://git-secret.io/
```


find email using commit :
https://ghintel.secrets.ninja/

advance search engine :
https://grep.app/
