must used linux utilities for master grep,sed,tr,ed,awk ,uniq,sort and vi.


awk : mostly used tool for text processing
```bash
syntax : awk 'pattern { action }' file  # basic syntax of the awk command


NR = No. of records/row
NF = No. of fields
$0 = Print everything
$1,$2 = field no

example : awk '{print $2}' sample.txt

awk '{print $4, $2}' sample.txt
awk '{print $NF}' sample.txt
awk '/expression/{print $0}' sample.txt
awk '{print NR,$0}' sample.txt
awk 'NR==6{print NR,$0}' sample.txt
awk 'NR==6,NR==10{print NR,$0}' sample.txt

# Using a delimiter other than space
awk -F',' '{ print $1 }' file      # use comma as delimiter
awk -F':' '{ print $2 }' file      # use colon as delimiter
awk -F'[,:]' '{ print $3 }' file   # use multiple delimiters

# Selecting lines that match a pattern
awk '/pattern/ { print }' file   # print lines that match pattern
awk '$1 == "foo" { print }' file # print lines where first field is "foo"
awk '$3 > 10 { print }' file     # print lines where third field is greater than 10

# Performing calculations
awk '{ sum += $1 } END { print sum }' file  # calculate sum of first field
awk '{ if ($1 > max) max = $1 } END { print max }' file  # calculate max of first field

# Using variables
awk -v var=value '{ print var }' file  # pass variable value to awk script

# Redirecting output
awk '{ print > "output.txt" }' file  # write output to file instead of screen
```


find : mostly used command for find and operation
```bash
find <location> <options>

# Search everything under current directory
find .

# Search in /etc
find /etc

# Find by exact name
find . -name "file.txt"

# Case-insensitive name search
find . -iname "file.txt"

# Wildcard search
find . -name "*.log"

# Multiple file extensions
find . -name "*.sh" -o -name "*.py"

# Files larger than 100 MB
find . -size +100M

# Files smaller than 10 KB
find . -size -10k

# Files exactly 1 GB
find . -size 1G

# Modified in last 7 days
find . -mtime -7

# Accessed more than 30 days ago
find . -atime +30

# Changed in last 2 hours
find . -cmin -120

# Files owned by user 'alice'
find . -user alice

# Files owned by group 'developers'
find . -group developers

# Files with 777 permissions
find . -perm 777

# Files with any execute bit set
find . -perm /111

# Directories only
find . -type d

# Symbolic links only
find . -type l

# Find empty files or directories
find . -empty

# .sh files owned by user alice
find . -name "*.sh" -user alice

# Run rm on all .log files
find . -name "*.log" -exec rm {} \;

# Run cp in batches (faster)
find . -name "*.jpg" -exec cp {} /backup/ +

# Limit search to current directory only
find . -maxdepth 1 -type f

# Search up to 3 levels deep
find . -maxdepth 3 -name "*.conf"

# Ignore shallow directories
find . -mindepth 2 -type d

# Regex: find images by extension
find . -regex ".*\.\(jpg\|png\|gif\)$"

# Regex case-insensitive
find . -iregex ".*readme.*"

# Files >100 MB modified in last 2 days
find . -size +100M -mtime -2

# Delete all empty directories
find . -type d -empty -delete

# Find broken symlinks
find . -xtype l

# Show files with modification times
find . -type f -printf "%T+ %p\n" | sort

# Tar all .log files
find . -name "*.log" -print0 | tar -czvf logs.tar.gz --null -T -

# Count number of .txt files
find . -name "*.txt" | wc -l

# Remove .tmp files efficiently with xargs
find . -name "*.tmp" -print0 | xargs -0 rm -f

```


grep command:
```bash
#search specific pattern
grep "error" /var/log/syslog
grep "error" /var/log/syslog /var/log/auth.log

#search recursive .
grep -r "search_string" /path/to/directory

#ignore case-sensitivity
grep -i "error" /var/log/syslog

#Count Number of Occurrences
grep -c "error" /var/log/syslog

#using regular expression pattern
grep "^error" /var/log/syslog
grep "error$" /var/log/syslog

#invert grep search
grep -v "search_string" filename

#search for multiple pattern.
grep -E "pattern1|pattern2" filename

#number the line that contain the search pattern
grep -n "Linux" welcome.txt

#Displaying number of lines before or after a search pattern Using pipes
ifconfig | grep -A 4 ens3
ifconfig | grep -B 4 ether


```




sort commands :
```bash
#acending sort
sort -o sorted.txt default.txt
sort default.txt > sorted.txt

#reverse order sort decending sort
sort -r default.txt

#remove duplicates
sort -u test.txt

#randomly arrage data
sort -R ordered.txt

```

Metacharacters in linux:
```bash
Metacharacter	Meaning / Use Case	Example

*	Wildcard: matches any string	ls *.txt → lists all .txt files
?	Wildcard: matches any single character	ls file?.txt → matches file1.txt, fileA.txt
[ ]	Character class for pattern matching	ls file[0-9].txt
{}	Brace expansion	echo file{1..3} → file1 file2 file3
~	Home directory shortcut	cd ~ → goes to /home/somx
$	Variable expansion	echo $USER
"	Double quotes: preserve spaces, allow expansion	echo "Hello $USER"
'	Single quotes: literal string, no expansion	echo 'Hello $USER'
\	Escape next character	echo \$USER → prints $USER
>	Redirect stdout to file (overwrite)	echo "Hi" > file.txt
>>	Redirect stdout to file (append)	echo "Hi" >> file.txt
<	Redirect stdin from file	wc -l < file.txt
`	`	Pipe: pass output to next command	`ls	grep txt`
;	Command separator	echo hi; echo bye
&&	Run next command only if previous succeeds	make && echo "Build OK"
`		`	Run next command only if previous fails	`make		echo "Build failed"`
()	Subshell grouping	(cd /tmp && ls)
{}	Command grouping (no subshell)	{ echo hi; echo bye; }
!	Negation or history expansion	!ls → runs last ls command
#	Comment	# This is a comment
:	Null command (no-op)	: > file.txt → truncates file
=	Assignment	x=42
&	Run in background	sleep 5 &
() in $(...)	Command substitution	echo $(date)
`...`	Legacy command substitution	echo `date`
EOF	Here-document delimiter	cat <<EOF ... EOF
^	Used in regex (not Bash itself)	grep '^start' file.txt
-	Option prefix	ls -l
%	Job control (e.g., %1)	fg %1
: in paths	Used in $PATH separator	echo $PATH
```



Regular Expression :
```bash
^ - beginning of line
$ - end of line

example:
cat names | grep ^R
cat names | grep am$

#using concatenation
grep press regex.txt 

#key characters in ERE:
| → alternation (logical OR)
( ) → grouping
? + {} → quantifiers (optional, one-or-more, ranges)
\ → escape (turns metacharacter into literal, or in BRE gives special meaning)

#Difference between bre(basic regular expression) and ere(extended regular expression)


grep "n|p" regex.txt
global|regular|expression|print

egrep  "n|p" regex.txt
global|regular|expression|print
Global Regular Expression Print

grep "n\|p" regex.txt (BRE with escaped \|)
In BRE, escaping \| turns it into alternation. So n\|p means n or p.
That matches both lines (same as grep -E "n|p").

egrep "n\|p" regex.txt (ERE, escaped \|)
In ERE the plain | is alternation. But you escaped it (\|), so the backslash neutralizes the special meaning and makes it a literal |

#final note
Use grep -E (or egrep) if you want readable regexes with |, +, ?, () etc.
If you escape a metacharacter in ERE you generally make it literal, so be careful: \| means different things in BRE vs ERE.


```


NFS:
```bash
#nfs server
sudo apt-get install nfs-kernel-server
sudo systemctl status nfs-kernel-server

#configuration file for nfs
cat /etc/exports
/exports/backup 10.10.10.0/255.255.255.0(rw,no_subtree_check)
/exports/documents 10.10.10.0/255.255.255.0(rw,no_subtree_check)

#client nfs
sudo apt-get install nfs-common
showmount --exports <nfs server ip address>

#make directory for nfs where you want to mount nfs dir
sudo mkdir /mnt/nfs
sudo mkdir /mnt/nfs/backup
sudo mkdir /mnt/nfs/documents

#mount the nfs to the client
sudo mount <ip address of server>:/export/backup /mnt/nfs/backup

#unmount the directory
sudo umount /mnt/nfs/backup
```


Shell variable:
```bash
$0    The filename of the current script
$n    n is positive decimal number($1 first arguemnt, $2 argument and so on)
$*    all argument
$@    all command line arugment
$?    The exit status of the last command
$$    The process number of current shell process Id
$!    The process number of the last background command.
```