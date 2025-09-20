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

```
