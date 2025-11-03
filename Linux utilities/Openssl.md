

```bash
openssl version
openssl help

#verfiy integrity and generate hash

openssl sha256 file.txt  #also use other hashing algorithm
openssl sha256 -hex -out openssl.sha256 openssl-1.1.1q.tar.gz
```