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
https://github.com/Alikhalkhali/programs-watcher
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

| Platform                  | Focus                       | Notes                                                 |
| ------------------------- | --------------------------- | ----------------------------------------------------- |
| **HackerOne**             | Web, Mobile, APIs, VDPs     | Most popular, public/private programs, top payouts    |
| **Bugcrowd**              | Web, APIs, IoT              | VRT-based scoring, great private scope and triage     |
| **Intigriti**             | EU-focused, Web & Mobile    | Strong private invites, responsive triage             |
| **YesWeHack**             | EU-based, various scopes    | VDPs + bug bounties, public/private programs          |
| **HackenProof**           | Web3 + TradFi               | Web apps, fintech, crypto-heavy focus                 |
| **Cobalt.io**             | Invite-only, pen-test style | Paid testers, “Pentest-as-a-service”, more structured |
| **Synack Red Team (SRT)** | High-end pentesting         | Must pass vetting & testing, very high-paying bugs    |
| Platform      | Notes                                                     |
| ------------- | --------------------------------------------------------- |
| **Immunefi**  | Smart contract & DeFi bounty platform (high rewards)      |
| **Code4rena** | Competitive auditing model (contest-based bounty hunting) |
| **Sherlock**  | Audit contests for Web3 projects                          |
| **0xPOSH**    | Aggregator for open Web3 bug bounty programs              |
| **ArmorFi**   | Web3-focused bounty system                                |

| Platform           | Purpose                                                         |
| ------------------ | --------------------------------------------------------------- |
| **OpenBugBounty**  | Report XSS + similar vulns (recognition only, sometimes bounty) |
| **Disclose.io**    | Aggregated VDPs, standardized safe harbor policies              |
| **HackerOne VDPs** | E.g., U.S. DoD, NATO, Gov orgs via HackerOne                    |

| Platform         | Region / Focus                | Notes                                    |
| ---------------- | ----------------------------- | ---------------------------------------- |
| **Bugv**         | India-focused                 | Regional bounties + training             |
| **JSec**         | Japan-specific programs       | JPN-only, legal Japanese bounty programs |
| **Secarma Labs** | UK-based VDPs                 | Some cash bounties, mostly disclosure    |
| **FrintLabs**    | Middle East/North Africa      | Regional bug bounty startup              |
| **SafeHats**     | Indian startups + enterprises | Enterprise + education bounties          |

| Company           | Program Page                                                                        |
| ----------------- | ----------------------------------------------------------------------------------- |
| **Google**        | [bughunters.google.com](https://bughunters.google.com)                              |
| **Facebook/Meta** | [facebook.com/whitehat](https://www.facebook.com/whitehat)                          |
| **Apple**         | [developer.apple.com/security-bounty](https://developer.apple.com/security-bounty/) |
| **Tesla**         | [tesla.com/about/legal#security](https://www.tesla.com/about/legal#security)        |
| **Microsoft**     | [microsoft.com/msrc](https://www.microsoft.com/en-us/msrc/bounty)                   |
| **GitHub**        | [github.com/security](https://github.com/security)                                  |
| Nasa              |                                                                                     |
| Red Bull          |                                                                                     |
| SAMSUNG           |                                                                                     |
| TOP tiers         |                                                                                     |

| Tool/Feed                               | What It Does                                     |
| --------------------------------------- | ------------------------------------------------ |
| **BugBountyRadar (Discord + Telegram)** | Scope & bounty alerts                            |
| **bb-monitor**                          | CLI scope monitor for H1/Bugcrowd/Intigriti      |
| **bounty-targets-data**                 | JSON feed of active scopes across platforms      |
| **chaos.projectdiscovery.io**           | Subdomain + recon data for public bounty targets |
