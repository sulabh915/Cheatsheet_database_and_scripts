

Bucket enumeration :
```bash
assetfinder --subs-only canva.com | httprobe | anew hosts; meg -d 1000 -v /; gf s3-buckets
ruby lazys3.rb <COMPANY> 
```


%c0 error character in url :
```bash
https://target.com/%c0
```

*check the source code for s3 bucket using s3 keyword*

some google dorking :
```bash
site:http://s3.amazonaws.com intitle:index.of.bucket
site:http://amazonaws.com inurl:".s3.amazonaws.com/"
site:.s3.amazonaws.com "Company"
intitle:index.of.bucket
site:http://s3.amazonaws.com intitle:Bucket loading
site:*.amazonaws.com inurl:index.html
Bucket Date Modified



site:s3.amazonaws.com "target.com"
site:*.s3.amazonaws.com "target.com"
site:s3-external-1.amazonaws.com "target.com"
site:s3.dualstack.us-east-1.amazonaws.com "target.com"
site:amazonaws.com inurl:s3.amazonaws.com 
site:s3.amazonaws.com intitle:"index of"  
site:s3.amazonaws.com inurl:".s3.amazonaws.com/"  
site:s3.amazonaws.com intitle:"index of" "bucket"

(site:*.s3.amazonaws.com OR site:*.s3-external-1.amazonaws.com OR site:*.s3.dualstack.us-east-1.amazonaws.com OR site:*.s3.ap-south-1.amazonaws.com) "target.com"
```

auotmated tools and command :
https://github.com/Atharv834/S3BucketMisconf
```bash
subfinder -d target.com -all -silent | httpx-toolkit -sc -title -td | grep "Amazon S3"
subfinder -d target.com -all -silent | nuclei -t /home/somx/.local/nuclei-templates/http/technologies/s3-detect.yaml

katana -u https://site.com/ -d 5 -jc | grep '\.js$' | tee alljs.txt
cat alljs.txt | xargs -I {} curl -s {} | grep -oE 'http[s]?://[^"]*\.s3\.amazonaws\.com[^" ]*' | sort -u


cewl https://site.com/ -d 3 -w file.txt
s3scanner -bucket-file file.txt -enumerate -threads 10 | grep -aE 'AllUsers: \[.*(READ|WRITE|FULL).*]'

```


github dorking :
```bash
org:target "amazonaws"
org:target "bucket_name" 
org:target "aws_access_key"
org:target "aws_access_key_id"
org:target "aws_key"
org:target "aws_secret"
org:target "aws_secret_key"
org:target "S3_BUCKET"
```


configure enviornment:
```bash
aws configure
```

