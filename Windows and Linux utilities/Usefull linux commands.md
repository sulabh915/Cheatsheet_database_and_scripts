	

usefull website to follow :
This website will explain about each commands parameters 
https://explainshell.com/

get clean help of each commands
https://tldr.sh/


basic linux command :
```bash
date
time
free
cd folder-path (.,..,/path-name)
pwd
```

cd shortcuts:
```bash
cd            Changes the working directory to your home directory .
cd -          Changes the working directory to the previous working directory
cd ~user_name Changes the working directory to the home directory of user_name . For example, 
cd ~ will change the directory to the home directory of user bob

```


> [!Remember] 
> If the last character of the prompt is a hash mark (#) rather than a dollar sign, the terminal session has superuser privileges. This means either we are logged in as the root user or we selected a terminal emulator that provides superuser (administrative) privileges.




> [!NOTE] Linux shortcuts
> - **`Ctrl + C`**: **Kill / Stop** the currently running command or process immediately.
> - **`Ctrl + Z`**: **Suspend / Pause** a process and push it to the background. _(Type `fg` to bring it back)_.
> - **`Ctrl + D`**: **Log out** of the current terminal session or send an EOF (End of File) signal.
> - **`Ctrl + L`**: **Clear the screen** completely (equivalent to typing the `clear` command).
> - **`Ctrl + S`**: **Stop output** to the screen (freezes the visual display without stopping the background command).
> - **`Ctrl + Q`**: **Resume output** after it was frozen by `Ctrl + S`
> - **`Tab`**: **Auto-complete** command names, file paths, or directories.
> - se **`Ctrl + A`** / **`Ctrl + E`** to move to the beginning or end of a line, **`Alt + B`** / **`Alt + F`** to move by a word, and **`Ctrl + U`**, **`Ctrl + K`**, or **`Ctrl + W`** to cut or delete text, which can then be pasted using **`Ctrl + Y`**
> - Use **`Ctrl + R`** to open reverse history search and **`Ctrl + G`** to exit it. You can also repeat the last command using `!!` or reuse the last argument with `!$`
> - **Quick Actions**: Access tools like the terminal via **`Ctrl + Alt + T`**, open the application menu with the **`Super`** key, lock the screen with **`Ctrl + Alt + L`**, or open the run console using **`Alt + F2`**
> - Remember the copy-and-paste trick! If you are using a mouse, you can double-click a filename to copy it and middle-click to paste it into commands.




ls commands: options 
```bash
-a  --all 
List all files, even those with names that begin with a period, which are normally not listed (that is, hidden)

-A --almost-all
Like the -a option except it does not list . (current directory) and .. (parent directory)

-d --directory
Ordinarily, if a directory is specified, ls will list the contents of the directory, not the directory itself . Use this option in conjunction with the -l option to see details about the directory rather than its contents

-F --classify
This option will append an indicator character to the end of each listed name, for example, a for ward slash (/) if the name is a directory

-h --human-readable
n long format listings, display file sizes in human readable format rather than in bytes

-S Sort results by file size
-t Sort by modification time
```

file command: check the type 
```bash
file picture.jpg
```


viewing files :
```bash
more /etc/passwd
less /etc/passwd
```


> [less] shortcuts
> 
> page up or b                      Scroll back one page
> page down or space          Scroll forward one page
> Up arrow                            Scroll up one line
> Down arrow                        Scroll down one line
> G                                          Move to the end of the text file
> 1G or g                               Move to the beginning of the text file
> /characters                         Search forward to the next occurrence of characters
> n                                         Search for the next occurrence of the previous search
> h                                         Display help screen
> q                                          Quit less


Directories on Linux Systems

| Directory                  | Comments                                                                                                                                                                                                                                                                                                                                                                                                                                  |
| :------------------------- | :---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `/`                        | The root directory, where everything begins.                                                                                                                                                                                                                                                                                                                                                                                              |
| `/bin`                     | Contains binaries (programs) that must be present for the system to boot and run. Note that modern Linux distributions have deprecated `/bin` in favor of `/usr/bin`.                                                                                                                                                                                                                                                                     |
| `/boot`                    | Contains the Linux kernel, the initial RAM disk image (for drivers needed at boot time), and the boot loader. Interesting files include `/boot/grub/grub.cfg` or `menu.lst`, which is used to configure the boot loader, and `/boot/vmlinuz` (or something similar), the Linux kernel.                                                                                                                                                    |
| `/dev`                     | This is a special directory that contains device nodes. “Everything is a file” also applies to devices. Here is where the kernel maintains a list of all the devices it understands.                                                                                                                                                                                                                                                      |
| `/etc`                     | The `/etc` directory contains all of the system-wide configuration files. Everything in this directory should be readable text. While everything in `/etc` is interesting, here are some all-time favorites: `/etc/crontab`, which defines when automated jobs will run on systems that use the cron program; `/etc/fstab`, a table of storage devices and their associated mount points; and `/etc/passwd`, a list of the user accounts. |
| `/home`                    | In normal configurations, each user is given a directory in `/home`. Ordinary users can only write files in their home directories. This limitation protects the system from errant user activity.                                                                                                                                                                                                                                        |
| `/lib`                     | Contains shared library files used by the core system programs. These are similar to dynamic link libraries (DLLs) in Windows. This directory has been deprecated in modern distributions in favor of `/usr/lib`.                                                                                                                                                                                                                         |
| `/lost+found`              | Each formatted partition or device using a traditional Linux filesystem, such as ext4, will have this directory. It is used in the case of a partial recovery from a filesystem corruption event. Unless something really bad has happened to our system, this directory will remain empty.                                                                                                                                               |
| `/media`                   | On modern Linux systems, the `/media` directory will contain the mount points for removable media such as USB drives, CD-ROMs, and so on, that are mounted automatically at insertion.                                                                                                                                                                                                                                                    |
| `/mnt`                     | On older Linux systems, the `/mnt` directory contains mount points for devices that have been mounted manually.                                                                                                                                                                                                                                                                                                                           |
| `/opt`                     | The `/opt` directory is used to install “optional” software. This is mainly used to hold commercial software products that might be installed on the system.                                                                                                                                                                                                                                                                              |
| `/proc`                    | The `/proc` directory is special. It’s not a real filesystem in the sense of files stored on the hard drive. Rather, it is a virtual filesystem maintained by the Linux kernel. The “files” it contains are peepholes into the kernel. The files are readable and will give us a picture of how the kernel sees the computer. Browsing this directory can reveal many details about the computer’s hardware.                              |
| `/root`                    | This is the home directory for the root account.                                                                                                                                                                                                                                                                                                                                                                                          |
| `/run`                     | This is a modern replacement for the traditional `/tmp` directory. Unlike `/tmp`, the `/run` directory is mounted using the tempfs filesystem type, which stores its contents in memory rather than on a physical disk.                                                                                                                                                                                                                   |
| `/sbin`                    | This directory contains “system” binaries. These are programs that perform vital system tasks that are generally reserved for the superuser. Note that modern Linux distributions have deprecated `/sbin` in favor of `/usr/sbin`.                                                                                                                                                                                                        |
| `/sys`                     | The `/sys` directory contains information about devices that have been detected by the kernel. This is much like the contents of the `/dev` directory but is more detailed including such things actual hardware addresses.                                                                                                                                                                                                               |
| `/tmp`                     | The `/tmp` directory is intended for storing temporary, transient files created by various programs. Some distributions empty this directory each time the system is rebooted.                                                                                                                                                                                                                                                            |
| `/usr`                     | The `/usr` directory tree is likely the largest one on a Linux system. It contains all the programs and support files used by regular users.                                                                                                                                                                                                                                                                                              |
| `/usr/bin`                 | `/usr/bin` contains the executable programs installed by the Linux distribution. It is not uncommon for this directory to hold thousands of programs.                                                                                                                                                                                                                                                                                     |
| `/usr/lib`                 | The shared libraries for the programs in `/usr/bin`.                                                                                                                                                                                                                                                                                                                                                                                      |
| `/usr/local`               | The `/usr/local` tree is where programs that are not included with the distribution but are intended for system-wide use are installed. Programs compiled from source code are normally installed in `/usr/local/bin`. On a newly installed Linux system, this tree exists, but it will be empty until the system administrator puts something in it.                                                                                     |
| `/usr/sbin`                | Contains more system administration programs.                                                                                                                                                                                                                                                                                                                                                                                             |
| `/usr/share`               | `/usr/share` contains all the shared data used by programs in `/usr/bin`. This includes things such as default configuration files, icons, screen backgrounds, sound files, and so on.                                                                                                                                                                                                                                                    |
| `/usr/share/doc`           | Most packages installed on the system will include some kind of documentation. In `/usr/share/doc`, we will find documentation files organized by package.                                                                                                                                                                                                                                                                                |
| `/var`                     | With the exception of `/tmp` and `/home`, the directories we have looked at so far remain relatively static; that is, their contents don’t change. The `/var` directory tree is where data that is likely to change is stored. Various databases, print spool files, user mail, and so on, are located here.                                                                                                                              |
| `/var/log`                 | `/var/log` contains logfiles, records of various system activity. These are important and should be monitored from time to time. The most useful ones are `/var/log/messages` and/or `/var/log/syslog` though these are not available on all systems. Note that for security reasons, some systems only allow the superuser to view logfiles.                                                                                             |
| `~/.config` and `~/.local` | These two directories are located in the home directory of each desktop user. They are used to store user-specific configuration and program state data for desktop applications.                                                                                                                                                                                                                                                         |

### Wildcard :

| Wildcard Syntax | Direct Meaning | Plain English Analogy | Example Command | What the Command Does | Example Matching Files |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `*` | **Zero or more** characters | *"Match anything here"* | `rm *.txt` | Deletes every file ending in `.txt` | `a.txt`, `log_2026.db`, `file` |
| `?` | **Exactly one** character | *"Fill in this single blank"* | `ls file?.txt` | Lists files with exactly one character after "file" | `file1.txt`, `fileA.txt`, `file_.txt` |
| `[abc]` | **One** character from the set | *"Pick either a, b, or c"* | `cp [ab]* /tmp/` | Copies files starting with `a` or `b` to the tmp directory | `a.txt`, `b.txt`, `c.txt` |
| `[a-z]` | **One** character in the range | *"Pick any letter from a to z"* | `ls [a-c]*` | Lists files starting with the letters `a`, `b`, or `c` | `a.txt`, `b.txt`, `m.txt` |
| `[!abc]` or `[^abc]` | **One** character **not** in the set | *"Pick anything EXCEPT a, b, or c"* | `ls [!a-z]*` | Lists files that do not start with a lowercase letter | `d.txt`, `1.txt`, `z.txt` |
| `[[:upper:]]` | Any **uppercase letter** | POSIX class for caps | `mv [[:upper:]]* Caps/` | Moves files starting with a capital letter to the Caps folder | `A`, `B`, `Z` |
| `[[:lower:]]` | Any **lowercase letter** | POSIX class for small letters | `ls [[:lower:]]*` | Lists files starting with a small letter | `a`, `g`, `x` |
| `[[:digit:]]` or `[0-9]` | Any **single digit** | POSIX class for numbers | `rm log_[0-9].txt` | Deletes single-digit log files from `log_0.txt` to `log_9.txt` | `0`, `3`, `9` |
| `[[:alnum:]]` | Any **alphanumeric character** | POSIX class for letters/numbers | `ls [[:alnum:]]*` | Lists files starting with any letter or number (ignores symbols) | `a`, `A`, `7` |


### Directory operation:

#### make directory:
```bash
mkdir dir1
mkdir dir1 dir2 dir3
```


#### cp :
```bah
cp item1 item2
```

| **Short Option** | **Long Option** | **Description** | **Best Used For** | **Command Example** |
| :--- | :--- | :--- | :--- | :--- |
| **`-a`** | `--archive` | Copies files and directories along with all of their **attributes** (ownership, permissions, timestamps). | Making exact backups. | `cp -a /src /dest` |
| **`-i`** | `--interactive` | **Prompts for confirmation** before overwriting an existing file. | Preventing accidental data loss. | `cp -i file.txt /dest` |
| **`-r`** | `--recursive` | **Recursively copies directories** and their entire contents. Mandatory for folders. | Copying folders and subfolders. | `cp -r /src_dir /dest` |
| **`-u`** | `--update` | Only copies files that **don't exist** or are **newer** than destination files. | Speeding up large file transfers. | `cp -u *.txt /dest` |
| **`-v`** | `--verbose` | Displays **informative progress messages** as the copy is being performed. | Tracking real-time actions. | `cp -v file.txt /dest` |

#### mv:
The mv command performs both file moving and file renaming, depend ing on how it is used
```bash
mv item1 item2
mv item... directory
```



| Command | Result |
| :--- | :--- |
| **`mv file1 file2`** | Move `file1` to `file2`. If `file2` exists, it is deleted and `file1` is renamed `file2`. If `file2` does not exist, `file1` is simply renamed `file2`. In either case, `file1` ceases to exist. |
| **`mv -i file1 file2`** | Same as the previous command, except that if `file2` exists, the user is prompted before it is deleted. |
| **`mv file1 file2 dir1`** | Move `file1` and `file2` into directory `dir1`. The directory `dir1` must already exist. |
| **`mv dir1 dir2`** | If directory `dir2` does not exist, rename directory `dir1` to `dir2`. <br><br>If directory `dir2` does exist, move directory `dir1` (and its contents) into directory `dir2`. |



#### rm:

| **Command** | **Description** | **Best Used For** |
| :--- | :--- | :--- |
| `rm file1` | Deletes `file1` **silently** without any prompts. | Quick removal of known files. |
| `rm -i file1` | Prompts the user for **confirmation** before deleting the file. | Preventing accidental data loss. |
| `rm -r file1 dir1` | **Recursively deletes** `dir1` (and its entire contents) along with `file1`. | Removing directories and folders. |
| `rm -rf file1 dir1` | Forces recursive deletion. If `file1` or `dir1` do not exist, `rm` **continues silently** without errors. | Nuclear cleanup of paths (use with extreme caution). |

#### Hard link and Soft link :

| **Feature** | **Hard Link** | **Soft Link (Symlink)** |
| :--- | :--- | :--- |
| **Command** | `ln target link_name` | `ln -s target link_name` |
| **Target Pointer** | Points directly to the **Inode** (same data block) | Points to the **Filename** (shortcut) |
| **If original is deleted** | **Data is safe** (accessible via the hard link) | **Link breaks** (becomes a dead/dangling link) |
| **Inode Number** | Shares the **same** inode as the original | Has a **different**, unique inode number |
| **File Size** | Same size as the original file | Tiny size (just stores the path text) |
| **Directory Support** | **No** (cannot link to folders) | **Yes** (can link to folders) |
| **Cross-Drive Support**| **No** (must stay on the same file system) | **Yes** (can span across different drives) |

```bash
[me@linuxbox playground]$ ls -l 
total 8
drwxrwxr-x 2 me me 4096 2025-01-15 15:17 dir1 
lrwxrwxrwx 1 me me 4 2025-01-16 14:45 dir1-sym -> dir1 
drwxrwxr-x 2 me me 4096 2025-01-15 15:17 dir2 
lrwxrwxrwx 1 me me 3 2025-01-15 15:15 fun-sym -> fun
```



### type,which,help,man,apropos,whereis,whatis :

| Command       | Definition / Interpretation                                                        | Syntax              | Example Command       | Example Output                                       |
| :------------ | :--------------------------------------------------------------------------------- | :------------------ | :-------------------- | :--------------------------------------------------- |
| **`type`**    | Indicates how the shell interprets a command name (builtin, file, alias, etc.).    | `type <command>`    | `type ls`             | `ls is aliased to 'ls --color=auto'`                 |
| **`which`**   | Locates and displays the path of the executable that will run (searches `$PATH`).  | `which <command>`   | `which grep`          | `/bin/grep`                                          |
| **`help`**    | Displays documentation and usage info specifically for shell builtin commands.     | `help <builtin>`    | `help cd`             | *[Detailed text guide on how `cd` works]*            |
| **`man`**     | Opens the format-formatted primary manual page for an external system program.     | `man <command>`     | `man find`            | *[Interactive manual viewer for the `find` utility]* |
| **`apropos`** | Searches man page descriptions for keywords when you don't know the exact command. | `apropos <keyword>` | `apropos "partition"` | `fdisk (8) - manipulate disk partition table`        |
| **`whereis`** | Locates the binary file, source code, and manual page files for a command.         | `whereis <command>` | `whereis ls`          | `ls: /bin/ls /usr/share/man/man1/ls.1.gz`            |
| **`whatis`**  | Displays a very brief, single-line text description from the command's man page.   | `whatis <command>`  | `whatis cp`           | `cp (1) - copy files and directories`                |










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

#check the syntax :
sudo exportfs -ra
	
#client nfs
sudo apt-get install nfs-common
showmount --exports <nfs server ip address>

#make directory for nfs where you want to mount nfs dir
sudo mkdir /mnt/nfs
sudo mkdir /mnt/nfs/backup
sudo mkdir /mnt/nfs/documents

#mount the nfs to the client
sudo mount <ip address of server>:/export/backup /mnt/nfs/backup
sudo mount -o rw,vers=3 192.168.154.130:/exports/documents /mnt/nfs/documents
sudo mount -t nfs4 <server>:/exported/folder /mnt/target -o rw,hard,intr


#unmount the directory
sudo umount /mnt/nfs/backup
```



Shell Script Variable:
```bash
$0    The filename of the current script
$n    n is positive decimal number($1 first arguemnt, $2 argument and so on)
$*    all argument
$@    all command line arugment
$#    Calculate length of positional parameter.
$?    The exit status of the last command
$$    The process number of current shell process Id
$!    The process number of the last background command.
IFS   Internal Field Sperator , we can sperate with everything like space , .
```


Common exit code in linux :
```bash
0    successful  execution
1    catch generic errors
2    improper command usage unable to execute the command
127  The issue in path resolution
130  Fatal error
255  Out of range
```

read command :
```bash
read -s -t 5 -p "Input your first name: " name
read -s -t 5 -p "input your age:" age
read -s -t 5 -p "Input the town you are from " town
echo "My name is $name"
echo "I am $age"
echo "I am from $town" 
```

select command:
```bash
select day in mon tue wed thu fri sat sun;
do
	echo "the day of the week is $day"
	break
done
```


conditional statement :
```bash
if [ 2 -gt 1 ]; then
		echo test passed
elif [ 1 -eq 1 ]; then
		echo second test passed
else
		echo test failed
fi

#using condition operators &&,|| and !
if [ $a = $b ] && [ $a = $c ]; then
             //code
fi 


# Case statement
# Signal number (expression)
case "$1" in

1)  echo "Sending SIGHUP signal....."
    kill -SIGHUP $2
    ;;
2)  echo  "Sending SIGINT signal....."
    kill -SIGINT $2
    ;;
3)  echo  "Sending SIGQUIT signal....."
    kill -SIGQUIT $2
    ;;
4) echo  "Sending SIGKILL signals....."
   kill -SIGKILL $2
   ;;
*) echo "Signal number $1 has not been processed"
   ;;
esac        

```


Looping statement :
```bash
while [ condition ];
do
    # statements
    # commands
done

a=7
while [ $a -gt 4 ];
do
    echo $a
    ((a--))
done
echo "Out of the loop"  

#reading file using read-while loops
while read line; do
		echo "$line"
done < /etc/passwd


#takeing userinput
while read line; do
		echo "$line"
done < "$1"


while IFS="," read id name age; do
		echo "id is $id"
		echo "name is $name"
		echo "age is $age"
done < test.csv

```


getopts:
```bash
#!/bin/bash

while getopts "u:p:" opt; do
  case $opt in
    u) echo "Username: $OPTARG" ;;
    p) echo "Password: $OPTARG" ;;
    \?) echo "Invalid option: -$OPTARG" ;;
  esac
done


```


Arrays:
```bash
#access array
numbers=(1 2 3 4)
echo $numbers
echo ${numbers[2]}
echo ${numbers[@]}

#slices array
echo ${numbers[@]:1}
echo ${numbers[@]:1:2}

#add element from array
numbers+=(5)
echo ${numbers[@]}

#remove element
unset numbers[2]

#create array from file ,days.txt containing mon-sun words in line by line
readarray days < days.txt
readarray -t days < days.txt #remove characters
echo ${days[@]}
echo ${!days[@]} #index
echo ${!days[@]@Q} #invisible character

readarray files < <(ls ~/)
```


### Sync your copy and paste :
```bash
rsync -av --progress /path/to/source/ /path/to/destination/
rsync -av --progress /home/user/mybackup/ /mnt/backup/ #network path
rsync -av --progress /home/user/mybackup/ user@192.168.1.100:/backup/ #using ssh
```




### Get information about pc :
```bash
#CPU :
lscpu — Detailed CPU architecture and flags
cat /proc/cpuinfo — Raw CPU info per core
nproc — Number of processing units available


#RAM:
free -h — Human-readable memory usage
vmstat -s — Memory statistics summary
cat /proc/meminfo — Detailed memory breakdown
  

#DISK: 
lsblk — Block devices and mount points
df -h — Mounted filesystem usage
du -sh /path/to/dir — Size of a specific directory
fdisk -l — Partition table (needs sudo)
ls -lh /dev/sd* — Disk device sizes
mount | column -t — Mounted filesystems neatly
cat /proc/partitions — Partition info

#system overview:
uname -a — Kernel and architecture
hostnamectl — Hostname and OS details
uptime — System uptime
top or htop — Live system resource usage
dmesg | less — Boot and kernel messages
who -a — Logged-in users and boot time

#hardware device:
lshw — Full hardware profile (use sudo)
lspci — PCI devices (network, graphics, etc.)
lsusb — USB devices
dmidecode — BIOS and motherboard info (needs sudo)
inxi -Fxz — Summary of system info (install if missing)

#network informmation:
ip a or ifconfig — IP addresses and interfaces
ip r or route -n — Routing table
ss -tuln — Listening ports
netstat -i — Interface stats
nmcli device show — NetworkManager device details

#Security & Kernel Modules
lsmod — Loaded kernel modules
getenforce — SELinux status (if applicable)
auditctl -s — Audit daemon status

#other userfull utilities
neofetch — Stylish system summary
glxinfo — GPU info (for X11 systems)
smartctl -a /dev/sdX — Disk health (SMART)


