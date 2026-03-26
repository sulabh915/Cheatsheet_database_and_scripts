
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


#### Static Routing:
```bash
R2# conf t
R2(config)# interface g0/0
R2(config-if)# ip address 192.168.12.2 255.255.255.0
R2(config-if)# no shutdown
R2(config-if)# interfce g0/1
R2(config-if)# ip address 192.168.24.2 255.255.255.0
R2(config-if)# no shutdown

## show current route
R2# show ip route

## Adding routes
###syntax ip route ip-address netmask next-hop
R1(config)#ip route 192.168.4.0 255.255.255.0 192.168.13.3
R1(config)#do show ip route 


##using with exit-interface of next hope
R2(config)#ip route 192.168.1.0 255.255.255.0 g0/0
R2(config)#ip route 192.168.4.0 255.255.255.0 g0/1 192.168.24.4

##adding default routes to the internet
R1(config)#ip route 0.0.0.0 0.0.0.0 203.0.113.2
R1(config)#do show ip route

R1(config)#do show ip int br
```


#### VLAN Configuration :

```bash

#Configure router for different vlan port
en
conf t
int g0/0
ip address 10.0.0.62
ip address 10.0.0.62 255.255.255.192
no shutdown


int g0/1
ip address 10.0.0.126 255.255.255.192
no shut

int g0/2
ip address 10.0.0.190 255.255.255.192
no shut

do sh ip int brief


#create vlan in switch
en
conf t
int range g0/1,f3/1,f4/1
switchport mode access
switchport mode access vlan 10

int range g1/1,f5/1,f6/1
sw mode ac
sw ac vlan 20

int range g2/1,f7/1,f8/1
sw mode ac
sw ac vlan 30

do show vl br

#change the name of vlans if created
vlan 10
name ENGINEERING
vlan 20
name HR
vlan 30
name SALES

do sh vlan br
```


VLAN Trunk configuration:
```bash
SW1(config)#interface g0/0
SW1(config-if)#switchport mode trunk
SW1(config-if)#switchport trunk encapsulation ?
SW1(config-if)#switchport trunk encapsulation dot1q
SW1(config-if)#switchport mode trunk
SW1(config-if)#show interfaces trunk
SW1(config-if)#show vlan brief


#allow , add and remove vlan in trunk port
SW1(config-if)#show trunk allowed vlan 10,30
SW1(config-if)#do show interfaces trunk
SW1(config-if)#switchport trunk allowed vlan add 20
SW1(config-if)#do show interfaces trunk
SW1(config-if)#switchport trunk allowed vlan remove 20
SW1(config-if)#do show interfaces trunk

#all option
SW1(config-if)#switchport trunk allowed vlan all
SW1(config-if)#do show interfaces trunk

#except option
SW1(config-if)#switchport trunk allowed vlan except 1-5,10
SW1(config-if)#do show interfaces trunk

SW1(config-if)#switchport trunk allowed vlan none
SW1(config-if)#do show interfaces trunk


#native vlan
SW1(config-if)#switchport trunk native vlan 1001


SW1#show vlan brief 
(The show vlan brief command shows the access ports assigned to each VLAN,NOT the trunk port that allow each VLAN use the "show interfaces trunk" command instead to confirm trunk ports).



```



Router on a Stick (ROAS)
```bash
R1(config)#interface g0/0
R1(config-if)#no shutdown

R1(config-if)#interface g0/0.10
R1(config-subif)#encapsulation dot1q 10
R1(config-subif)#ip address 192.168.1.62 255.255.255.192

R1(config-subif)#interface g0/0.20
R1(config-subif)#encapsulation dot1q 20
R1(config-subif)#ip address 192.168.1.126 255.255.255.192

R1(config-subif)#interface g0/0.30
R1(config-subif)#encapsulation dot1q 30
R1(config-subif)#ip address 192.168.1.190 255.255.255.192
```