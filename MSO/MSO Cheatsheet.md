

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


## Migration from Google workspace to MSO :

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

### Migration 

- Properly add domain and verify to microsoft admin 365 login

# Google Workspace to Microsoft 365 Migration Guide

A step-by-step technical cheat sheet for migrating users, emails, calendars, and contacts from Google Workspace to Microsoft 365 Exchange Online using automated service accounts.

---

## 🛠️ Phase 1: Google Cloud Console Configuration

### 1. Project and Service Account Setup
1. Log in to the [Google Cloud Console](https://google.com).
2. Click the project dropdown (top left) and select **New Project**.
3. Enter your **Project Name** and click **Create**.
4. In the top search bar, search for and select **Service Accounts**.
5. Click **Create Service Account** and assign these roles:
   * `Owner`
   * `Service Account Admin`
6. Click **Done** to finalize the creation.

### 2. Bypass Organization Policy Blocks (If Keys Are Restricted)
If you see the error: *"An organization policy that blocks service account key creation has been enforced..."*

#### Fix 1: Missing Permissions ("You don't have permission to change the settings")
1. Click the project dropdown at the top left and select your **Primary Domain/Organization** level (instead of the project level).
2. Go to **IAM & Admin** > **IAM**.
3. Click Grant access -> **Add Principals** and add your admin email.
4. Assign the following roles to yourself:
   * `Organization Policy Administrator` (Crucial for editing policies)
   * `Owner`
   * `Billing Account Creator`
   * `Project Creator`

#### Fix 2: Disable the Key Restrictions
1. Switch back to your migration project or organization view.
2. Go to **IAM & Admin** > **Organization Policies**.
3. Search for the filter/policy: **"Disable service account key creation"**.
4. Click on the policy and select **Manage Policy** or **Edit Policy**.
5. Under **Enforcement**, select **Off** (or set to **Not Enforced**).
6. Click **Set Policy** / **Save**.

### 3. Generate the JSON Credentials Key
1. Go back to **Service Accounts** within your project.
2. Locate your newly created service account.
3. Under the **Actions** column (three vertical dots), click **Manage Keys**.
4. Click **Add Key** > **Create New Key**.
5. Select **JSON** format and click **Create**. 
6. *Note: The JSON file will download automatically. Keep this file secure; you will need it for Microsoft 365.*
7. *Tip: If it still blocks you, wait 2–5 minutes for the organization policy change to propagate globally, then retry.*

### 4. Enable Required Google APIs
Search for each of the following APIs in the top Cloud Console search bar, open them, and click **Enable**:
* 📧 **Gmail API**
* 📅 **Google Calendar API**
* 👥 **Google People API** (for Contacts)

---

## Ⓜ️ Phase 2: Microsoft 365 Admin Center Configuration

### 1. Step up the Automated Migration Batch
1. Log in to the [Exchange Admin Center (EAC)](https://microsoft.com).
2. In the left navigation menu, go to **Migration** > **Add migration batch**.
3. Enter a unique **Migration Batch Name** and choose **Migration to Exchange Online** as the path.
4. Select **Google Workspace (Gmail) migration** as the migration type.

### 2. Automate Prerequisites and Link Tenant
1. Under **Prerequisites for Google Workspace migration**, choose **Automate the configuration of your Google Workspace for migration**.
2. Click **Automate**.
3. The wizard will output a unique **ClientId** and a list of API **Scopes**. 
4. Copy these values. Go to your Google Workspace Admin Console (`admin.google.com`) > **Security** > **Access and data control** > **API controls** > **Manage Domain Wide Delegation**.
5. Add a new client, paste the **ClientId**(service account you just created), and paste the **Scopes** to authorize Microsoft 365 to access your Google data.

### 3. Endpoint and User Import
1. Return to the Microsoft 365 migration wizard and select **New Migration Endpoint**.
2. Provide your primary **Google Workspace Admin Email ID**.
3. Upload the **JSON key file** you downloaded from Google Cloud in Phase 1.
4. Upload your prepared **CSV file** containing the list of target email addresses mapping the Google source mailboxes to the Microsoft destination mailboxes.
5. Choose your target delivery options, save, and **Start** the migration batch.




```bash
PSA: Google blocks service account key creation by default to enable it if you're a new google workspace admin do this: 
1. Navigate to IAM and add the role "Organization Policy Administrator" (This allows you to update policies) 
2. Navigate to Organization Policies and search for "Disable service account key creation" 
2.1 Click edit policy it won't be grayed out now. 2.2 Click the drop down "Not enforced" 2.3 Click "Off" 2.4 Click "Set POLICY"
```


#### MSO To Google Workspace Migration

Phase 1: Office 365 / Exchange Prep

- **Admin Account**: Ensure you have a global admin account for your Microsoft tenant.
- **Impersonation Rights**: Assign the `ApplicationImpersonation` role to your Microsoft admin account via the Exchange Admin Center.
- **User List**: Create a CSV file mapping old emails to new emails (Format: `SourceEmail,TargetEmail`). 

Phase 2: Google Workspace Prep

- **Admin Access**: Log into your Google Admin Console.
- **Create Users**: Ensure all target user accounts are active and have Google Workspace licenses assigned.
- **Enable Migration**: Search for **Data Migration** in the Admin Console search bar. 

Phase 3: Configure the Bulk Connection

1. **Select Source**: Click **Set Up Data Migration** and choose **Microsoft Office 365** (or Exchange Server) as your migration source.
2. **Choose Connection**: Select **Auto-select (Recommended)** for the connection protocol.
3. **Authorize**: Click **Authorize** and sign into your Microsoft Global Admin account to link the systems.
4. **Set Filters**: Choose your date range limits and specify whether to import deleted/junk folders. 

Phase 4: Upload and Execute Bulk Migration

- **Bulk Upload**: Click **Add Users**, then select **Upload CSV file**.
- **Select File**: Attach the CSV mapping file you created in Phase 1.
- **Validate**: Review the uploaded list to ensure all source and target emails match perfectly.
- **Start Migration**: Click **Start** to begin migrating all accounts simultaneously.
- **Monitor Live**: Watch the progress bar, item count, and error logs directly from the Google Admin dashboard


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



### Disable MFA 

**Step 1: Turn off Per-User MFA (Legacy Settings)**This step removes the old, traditional style of forcing MFA on a single user.

1. **Log in**: Go to the Microsoft 365 Admin Center using your administrator credentials.
2. **Navigate to Users**: Look at the left-hand navigation menu. Click on **Users**, then select **Active users**.
3. **Open MFA Settings**: Look at the top horizontal menu bar. Click the **Multi-factor authentication** button. _(Note: If you do not see it, click the three dots `...` icon to find it hidden in the dropdown)._
4. **Locate the User**: A new browser tab will open displaying the legacy MFA service page. Scroll or use the search bar to find your specific user. Check the box next to their name.
5. **Disable MFA**: Look at the quick steps panel on the right side of the screen. Click **Disable**.
6. **Confirm**: A confirmation pop-up will appear. Click **Yes** to save the changes

**Step 2: Exclude the User from Conditional Access Policies**  **disable the all policy** ,If your company uses Entra ID Premium licenses, MFA is managed via global IT rules called Conditional Access. You must create an exception for this specific user.

1. **Log in**: Open the Microsoft Entra Admin Center.
2. **Find Policies**: In the left sidebar, navigate to **Protection** > **Conditional Access** > **Policies**.
3. **Identify MFA Rules**: Look at the list of active policies. Identify any policy that is marked as **On** and targets MFA (often named "Require MFA" or "Block Legacy Auth").
4. **Edit the Policy**: Click directly on the name of that policy. Under the **Assignments** section, click on **Users**.
5. **Add Exclusion**: You will see an "Include" tab and an "Exclude" tab. Switch to the **Exclude** tab. Check the box for **Users and groups**. Search for your specific user's name, select them, and click **Select**.
6. **Save Changes**: Scroll to the very bottom of the screen and click **Save**

**Step 3: Check "Security Defaults" (Crucial Overrule)**If Microsoft's baseline security setting called "Security Defaults" is turned on, it overrides everything else. **It forces MFA on everyone, and you cannot exempt a single person.**

1. **Navigate to Properties**: In the Entra Admin Center, go to **Identity** > **Overview** > **Properties**.
2. **Check Status**: Scroll to the bottom of the page and click **Manage security defaults**.
3. **Evaluate Your Options**:  
    ◦ **If it says "Enabled"**: You cannot disable MFA for just one person. To bypass MFA for this user, you would have to turn Security Defaults off for the _entire company_ (which makes your organization highly vulnerable to hacking).  
    ◦ **The Solution**: If you must exempt this user, you should disable Security Defaults here, buy a premium license, and use **Step 2 (Conditional Access)** instead to safely manage exclusions.

**Step 4: Clear Existing MFA Prompts (Reset the State)**If the user's phone or browser is stuck in an error loop asking for MFA codes, you must clear out their active login tokens.

1. **Find the Profile**: In the Entra Admin Center, go to **Identity** > **Users** > **All Users**.
2. **Select User**: Click on the specific user's name to open their profile details.
3. **Go to Authentication**: On the left-side sub-menu, click on **Authentication methods**.
4. **Revoke Sessions**: Look at the top action bar and click **Revoke sessions**. This instantly logs the user out of all devices (Outlook, Teams, phones). When they log back in, the system will check the new settings and let them in using only their password.