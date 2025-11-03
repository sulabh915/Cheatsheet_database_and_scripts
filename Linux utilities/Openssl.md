

```bash
openssl version
openssl help

#verfiy integrity and generate hash of files

openssl sha256 file.txt  #also use other hashing algorithm
openssl sha256 -hex -out openssl.sha256 openssl-1.1.1q.tar.gz
openssl sha256 -hex -out -Cats.sha256 Cats.txt


```