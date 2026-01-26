
Types of console :
```bash
User EXEC        → Switch>
Privileged EXEC  → Switch#
Global Config    → Switch(config)#
```


> [!NOTE] Using TAB button  or ? for help commands
> Use tab to auto complete the commands
> Use ? for all commands
> Use e? for commands recommandation


- Go into privilege mode:
```bash
Router>enable
Router#
```

- Go into configuration mode:
```bash
Router>enable
Router#conf t or configure terminal
Router(config)#
```

- Set the password insecure method:
```bash
Router>enable
Router#conf t or configure terminal
Router(config)#enable password <password is here>
exit
exit
```

- show running config this disclose the password:
```bash
Router>enable
Router# show running-config
Router# show startup-config
Router# write
Router# write memory
Router# copy running-config startup-config
```

- secure the password using the service:
```bash
Router# conf t
Router(config)#service password-encryption
exit
Router#show running-config (encrypted but insecure)
```

- using enable secret to encrypt password using MD5 hash
```bash
Router(config)#enable secret Cisco
Router(config)#do sh run (encrypted md5 hash)
```