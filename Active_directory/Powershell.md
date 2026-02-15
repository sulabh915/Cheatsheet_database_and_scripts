

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

