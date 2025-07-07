
Collecting urls :
```bash
cat subs.txt | gau --threads 50 > gau-raw.txt
cat subs.txt | waybackurls > wayback-raw.txt
cat gau-raw.txt wayback-raw.txt | anew all-urls.txt
```

looking for vulnerable  files :
```bash
cat all-urls.txt | grep -Ei "\\.(php|aspx|jsp|bak|env|git|json|config|sql|log)$" | anew filtered-files.txt
cat all-urls.txt | grep "\\?" | anew urls-with-params.txt
cat all-urls.txt | grep -Ei "\\.js$" | grep -vE "jquery|bootstrap|analytics" | anew js-files.txt
cat js-files.txt | httpx -status-code -silent -mc 200 | anew live-js.txt


```