
##### Migration from Google workspace to MSO :

- Add domain name (create subdomain for mail routing with parent domain)
- select user alias domain
- go to mso 365 and add domain
- same subdomain with parent domain created in google workspace
- verified the domain with dns provider
- create another subdomain for example www.gws.m365concepts.com (route mso365 to google workspace)
- go to the microsoft exchange recipients -> contains then create user  with created subdomain like test1@gws.m365concepts.com
- click on created account -> others ->manage email address types -> type email name with  tenant added domain like "test3@m365concepts.com" do it for all user account
- good to console.cloud.google.com/cloud-resource-manager
- Create project after creating project select project add principle in new principle type admin account email address
- Role assing and search project creator then add another permission search for create service account.
- go to api services search for gmail api  , calender api and contact api
- now go the mso 365 exchange then go to migration then verify all prerequists
- add api scopes show in mso 365
- add target delivery email 
- add licenses to user accounts and add mx record for domain


```bash
PSA: Google blocks service account key creation by default to enable it if you're a new google workspace admin do this: 
1. Navigate to IAM and add the role "Organization Policy Administrator" (This allows you to update policies) 
2. Navigate to Organization Policies and search for "Disable service account key creation" 
2.1 Click edit policy it won't be grayed out now. 2.2 Click the drop down "Not enforced" 2.3 Click "Off" 2.4 Click "Set POLICY"
```


##### Import pst and merge ot other account:

```
To create import jobs, you must be assigned the Mailbox Import Export role in Exchange Online.
```
###### Fix

Login to:

- [Exchange Admin Center](https://admin.exchange.microsoft.com?utm_source=chatgpt.com)

Then:

1. Go to:
    ```
    Roles → Admin roles
    ```
2. Open:
    ```
    Organization Management
    ```
3. Click:
    ```
    Permissions
    ```

4. Add:
    ```
    Mailbox Import Export
    ```
5. Save.

Alternatively create a new Role Group and assign:

```
Mailbox Import Export
```

to your admin account.
### Important

After assigning the role, Microsoft states it can take:

```
Up to 24 hours
```

for the permission to become active.

Then return to:

- Microsoft Purview Import

and you should see the option to create a PST Import Job.