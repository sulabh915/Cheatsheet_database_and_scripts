
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