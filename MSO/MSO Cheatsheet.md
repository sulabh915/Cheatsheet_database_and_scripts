

##### Licensing
|Feature|Microsoft 365 Personal|Microsoft 365 Family|Microsoft 365 Business|Office Home 2024 (Home & Student)|Office Home & Business 2024|Office Professional 2024|
|---|---|---|---|---|---|---|
|Word, Excel, PowerPoint|✅|✅|✅|✅|✅|✅|
|Outlook|✅|✅|✅|❌|✅|✅|
|OneNote|✅|✅|✅|✅|✅|✅|
|Publisher & Access|❌|❌|Some business plans only|❌|❌|✅|
|OneDrive Cloud Storage|1 TB|Up to 6 TB (1 TB/user)|1 TB/user|❌|❌|❌|
|Exchange Email|❌|❌|✅|❌|❌|❌|
|SharePoint|❌|❌|✅|❌|❌|❌|
|Microsoft Teams|❌|❌|✅ (plan dependent)|❌|❌|❌|
|License Type|Subscription|Subscription|Subscription|One-time purchase|One-time purchase|One-time purchase|
|Users|1|Up to 6|1 per license|1 PC|1 PC|1 PC|
|Approx. Price (India)|₹6,899/year|₹8,199/year|₹145–₹1,880/user/month*|₹10,999 one-time|~₹27,999 one-time|~₹47,999 one-time|


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


##### Import pst and merge to other account:

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


##### Increase the storage of one drive :

- login to microsoft 365 admin 
- go to the sharepoint -> settings
- click on onedrive (storage limit) increase to 1024 tp
- now go the active user in admin center
- now click on that particular user go the tap called onedrive
- then increase the storage limit.

## Enable archive 
##### Step 1: Verify License

The user must have a license that supports Online Archiving, such as:

- Exchange Online Plan 2
- Microsoft 365 E3/E5
- Microsoft 365 Business Premium (with archiving support)
- Exchange Online Archiving add-on

---

###### Step 2: Enable Archive Mailbox in Exchange Admin Center

1. Open:
    - [Exchange Admin Center](https://admin.exchange.microsoft.com?utm_source=chatgpt.com)
2. Go to:

```
Recipients → Mailboxes
```

3. Select the user.
4. Open:

```
Others
```

5. Click:

```
Manage mailbox archive
```

6. Turn:

```
Mailbox archive = Enabled
```

7. Click **Save**.

---

###### Step 3: Wait for Provisioning

It can take a few minutes to several hours for the archive mailbox to appear.

After activation, Outlook and Outlook Web will show:

```
Online Archive - User Name
```

below the primary mailbox.

---

##### PowerShell Method

If you prefer PowerShell:

```
Connect-ExchangeOnlineEnable-Mailbox -Identity user@domain.com -Archive
```

Check status:

```
Get-Mailbox user@domain.com | Select ArchiveStatus
```

If it returns:

```
Active
```

the archive mailbox is enabled.


### Creating retensional policy to move emails
##### 1. Enable Online Archive

First verify that the archive mailbox is enabled for the user.

Exchange Admin Center:

```
Recipients → Mailboxes → Select User → Others → Manage mailbox archive
```

Status should be **Enabled**.

---

### 2. Create a Retention Policy

Go to:

- [Microsoft Purview Portal](https://compliance.microsoft.com?utm_source=chatgpt.com)

Then:

```
Data lifecycle management→ Exchange (legacy)→ MRM Retention Tags
```

Create a tag such as:

```
Move to Archive after 365 days
```

or

```
Move to Archive after 730 days
```

Action:

```
Move item to archive
```

---

### 3. Create / Modify Retention Policy

Create a retention policy and add the archive tag.

Example:

```
Archive after 2 years
```

---

### 4. Assign Policy to User

Exchange Admin Center:

```
Recipients→ Mailboxes→ Select User→ Mailbox→ Retention Policy
```

Select the policy you created.

---

### 5. Run Managed Folder Assistant (Optional)

To speed up processing:

```
Connect-ExchangeOnlineStart-ManagedFolderAssistant -Identity user@domain.com
```

This can start moving emails sooner instead of waiting for Microsoft's background processing.
