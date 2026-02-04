
#### Configure switch

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


 - show mac address table or clear mac address-table dynamic
```bash
show mac address-table
clear mac address-table dynamic
```

- show interface information in switch
```bash
en
sh ip int br
show interface status
do sh int status

##configure speed and duplex
SW#conf t
SW1(config)#int f0/1
SW1(config-if)#speed ?
SW1(config-if)#duplex ?
SW1(config-if)#duplex full
SW1(config-if)#description ## to R1 ##

##configure multiple interface range
SW1(config)#interface range f0/5-12
SW1(config-if-range)#description ## not in use ##
SW1(config-if-range)#shutdown

#by using this these interface are administrativily shutdown
SW1(config)#int range f0/5-6, f0/9-12
SW1(config-if-range)#no shut
SW1(config-if-range)#do sh int status 

```



#### Configure Router :

```bash
Router>
Router>en
Router(config)#hostanem R1
Router(config)#show ip interface brief
Router(config)#do show ip interface brief
Router(config)#interface gigabitEthernet 0/0
Router(config-if)#ip address 15.255.255.254 255.0.0.0
Router(config-if)#description ## to sw1 ##
Router(config-if)#no shutdown
Router(config-if)#int g0/1
Router(config-if)#ip add 182.98.255.254 255.255.0.0
Router(config-if)#description ## to SW2 ##
Router(config-if)#no shut
Router(config-if)#int g0/2
Router(config-if)#ip add 201.191.20.254. 255.255.255.0
Router(config-if)#description ## to SW3 ##
Router(config-if)#end
Router#sh ip int br
Router#sh run
Router#copy running-config start
Router#write mem
```