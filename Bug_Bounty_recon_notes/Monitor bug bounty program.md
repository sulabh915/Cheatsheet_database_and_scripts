```bash
for platform in hackerone bugcrowd intigriti; do
  echo -e "\n\033[1;36m==============================\n[$platform Programs]\n==============================\033[0m"
  curl -s "https://raw.githubusercontent.com/arkadiyt/bounty-targets-data/master/data/${platform}_data.json" | jq -r '.[].url'
done

```

```bash
chaos-client -d example.com -key $CHAOS_KEY | httpx -silent
```

```bash
#!/bin/bash

domain=$1
date=$(date +%F)

mkdir -p ~/recon/$domain/$date

subfinder -d $domain -silent | tee ~/recon/$domain/$date/subs.txt
httpx -l ~/recon/$domain/$date/subs.txt -silent | tee ~/recon/$domain/$date/alive.txt
nuclei -l ~/recon/$domain/$date/alive.txt -o ~/recon/$domain/$date/nuclei.txt

#Don’t run this once. Cron it. Watch for changes.
#Bugs don’t just exist — they appear when changes happen.
#This script collects fresh subdomains, filters live ones, and scans them with Nuclei daily.
#Set it on daily cron. Let your system watch while you sleep.
```


Google dorking :
```bash
inurl:bug-bounty-program site:company.com
intext:responsible disclosure program
site:github.com "bug bounty"
site:medium.com "bug bounty report"
"Submit a vulnerability" inurl:security

```
https://undercodetesting.com/how-to-find-self-hosted-bug-bounty-programs-using-google-dorking/?form=MG0AV3


Bug Bounty resources :

Government sites.
private RVDP program
Open bug bounty
Bug bounty platform
Self Hosted bug bounty program
Community Monitoring	Reddit, Discords, Telegram (e.g., BBRadar)




github for bug bounty target :
https://github.com/sehno/Bug-bounty/tree/master
https://github.com/arkadiyt/bounty-targets-data
https://github.com/sushiwushi/bug-bounty-dorks/blob/master/dorks.txt



website for find bug bounty target :
https://bbradar.io/
https://www.zoomeye.ai/bugbounty
https://firebounty.com/
https://disclose.io/programs/
https://hackerone.com/directory/programs
https://bugcrowd.com/engagements
https://yeswehack.com/programs
https://www.intigriti.com/researchers/bug-bounty-programs


```bash
wget https://raw.githubusercontent.com/arkadiyt/bounty-targets-data/refs/heads/main/data/domains.txt
cat domains.txt | awk -F '.' '{print $(NF-1)"."$NF}' | grep -Eo '([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}' | sort -u > main_domains
grep -Eo '\b([0-9]{1,3}\.){3}[0-9]{1,3}\b' domains.txt > ips.txt
```


```bash
chaos-client -d example.com -key $CHAOS_KEY | httpx -silent

curl -s https://raw.githubusercontent.com/arkadiyt/bounty-targets-data/master/data/hackerone_data.json | jq '.[].program_url'
```


https://su6osec.medium.com/how-to-build-a-bug-bounty-target-list-that-actually-gets-you-bugs-2025-guide-626fe67497fa


- The secret to bug bounty success? Finding the right program & target before anyone else! 
- By targeting newer or less-tested programs, you put yourself in a less competitive and more rewarding environment.
- Look for Programs That Reward All Findings



Before selecting any target make sure they fulfill this requirement :
![[Pasted image 20250728052749.png]]