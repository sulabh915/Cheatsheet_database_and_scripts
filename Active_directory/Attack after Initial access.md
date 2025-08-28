
create service principle name :
```bash
setspn -a <Domain hostname>/<Account_name>.<Domain name>:<port> <Domain>\<Account name>
```


identify service principle name account :
```bash
setspn -T UAP.local -Q */*
```

