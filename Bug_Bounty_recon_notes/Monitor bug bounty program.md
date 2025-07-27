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



Bug Bounty resources :
https://www.zoomeye.ai/bugbounty
https://firebounty.com/
https://disclose.io/programs/
https://github.com/sehno/Bug-bounty/tree/master
https://github.com/arkadiyt/bounty-targets-data
```bash
wget https://raw.githubusercontent.com/arkadiyt/bounty-targets-data/refs/heads/main/data/domains.txt
cat domains.txt | awk -F '.' '{print $(NF-1)"."$NF}' | grep -Eo '([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}' | sort -u > main_domains
```