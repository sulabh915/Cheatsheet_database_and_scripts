

Get Help Command :
```bash
Get-Help -Name Get-Command -Full
Get-Help -Name Get-Command -Detailed
Get-Help -Name Get-Command -Examples
Get-Help -Name Get-Command -Online
Get-Help -Name Get-Command -Parameter Noun
Get-Help -Name Get-Command -ShowWindow
```

```bash
Get-Command -Name Get-Process
Get-Command -Verb Get
Get-Command -Noun Service
Get-Command -Module Microsoft.PowerShell.Management
Get-Command -All
Get-Command -Syntax
```

```bash
Get-Help Get-Process -Parameter Name
Get-Help Get-Process -Examples
Get-Help Get-Process -Syntax
```

Symbol	Meaning
[ ]	                       Optional
< >	                       Required value
{ }	                       Allowed values
[] after type	   Multiple values
Multiple SYNTAX blocks	Different parameter sets

using Get-Command:

```bash
Get-Command -Name Get-Command -Syntax
help Get-Command -Full
Get-Command -Name *service*
Get-Command -Name *service* -CommandType Cmdlet, Function, Alias, Script
Get-Command -Noun Process

#use pipline with Get-Member command to it's cmdlet object for example
Get-service | Get-member #it also display object along with property and methods

Get-Command -ParameterType ServiceController
```

Get-Member :
Get-Member lets you look inside PowerShell objects so you know what data they contain and what actions you can perform on them.
```bash
Get-Service -Name w32time
Get-Service-Name w32time | Get-Member
(Get-Service -Name w32time).Status


Get-Process | Get-Member
Get-ChildItem | Get-Member
Get-Service | Get-Member


Get-Service -Name w32time | Select-Object -Property *
Get-Service -Name w32time |
    Select-Object -Property Status, Name, DisplayName, ServiceType
Get-Service -Name w32time |
    Select-Object -Property Status, DisplayName, Can*
    
Get-Service -Name w32time | Get-Member -MemberType Method
(Get-Service -Name w32time).Stop()

Get-Service -Name w32time | Start-Service -PassThru


```


```bash
Get-Process |
Sort-Object CPU -Descending |
Select-Object -First5
```


```bash
Get-Service | Where-Object {
$_.Status -eq  "Stopped" -or $_.StartType -eq "Disabled"
}
```


```bash
Get-Process|
Where-Object Working Set -gt 300MB |
Sort-Object Working Set-Descending |
Select-Object -First5 Name,
@{Name="MemoryMB";Expression={[math]::Round($_.WorkingSet/1MB,2)}}
```


```bash
Get-Service|Where-Object {
$_.Status -eq "Stopped" -or $_.StartType -eq "Disabled"
}
```


```bash
Get-Service | Format-Table Name,Status -AutoSize


Get-Process|
Where-ObjectCPU -gt 1|
Format-Table Name,CPU


Get-Service | Format-Table Name,Status | Get-Member
```

Where-object , Select-object and Sort-object
```bash
Get-Service| Where-ObjectStatus-eq"Running"| Select-ObjectName,Status
```

The $_ Automatic Variable (Core Concept)
```bash
Get-Service|Where-Object {$_.Status-eq"Running" }

Get-Process|Where-Object { $_.CPU-gt10-and$_.WorkingSet-gt200MB }

Get-Service | Where-Object { $_.Status -eq "Stopped" -or $_.StartType -eq "Disabled" }

Get-Process|Select-ObjectName, @{Name="MemoryMB";Expression={$_.WorkingSet/1MB}}

Get-Process | Sort-Object CPU -Descending| Select-Object -First5

Get-Service|Select-ObjectName

	Get-Process| Where-ObjectCPU-gt1| Group-ObjectProcessName
```


```bash
Get-ExecutionPolicy
Get-ExecutionPolicy -List

Set-Execution Policy RemoteSigned
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser

```

```bash
$procs = get-process 
$procs2 = get-process 
Compare-Object -ReferenceObject $procs -DifferenceObject $procs2 -Property Name
```

```bash
Get-PSProvider
Get-PSDrive
```


#####Comparison Operators

```bash
"Hello" -eq "hello"
10 -ge 10

2 -lt 5

5 -le 5

"PowerShell" -like "Power*"

"PowerShell"- not like "*cmd"
```


####Looping
| Loop Type | Question It Asks |
| --- | --- |
| `for` | “Repeat this a specific number of times.” |
| `foreach` | “Do this for each item in a collection.” |
| `while` | “Keep going while this is true.” |
| `do while` | “Run once, then keep going while true.” |
| `do until` | “Run once, until this becomes true.” |



```bash
for ($i=1;$i-lt5;$i++) {
Write-Output"Sleeping for$i seconds"
Start-Sleep-Seconds$i
}


do {
# code
}
until (condition)


```
| Loop | Keeps Running When |
| --- | --- |
| do until | Condition is FALSE |
| do while | Condition is TRUE |


```bash
while ($date.DayOfWeek-ne'Thursday') {
$date=$date.AddDays(1)
}
```


