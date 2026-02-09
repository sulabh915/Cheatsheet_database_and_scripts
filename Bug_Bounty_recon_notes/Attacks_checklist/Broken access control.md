
Broken access control happens when a system or application doesn't properly enforce who is allowed to do what. This means users can access or perform actions they shouldn't, like viewing someone else's private data, changing sensitive settings, or accessing admin-only features.

Authentication                 : confirms that the user is who they say they are.
Session management   : identifies which subsequent HTTP requests are being made by that same user.
Access control                 : determines whether the user is allowed to carry out the action that they are attempting to perform.

#### Vertical access controls( Privilege escalation):
Vertical access controls are mechanisms that restrict access to sensitive functionality to specific types of users.

Role-Based Access Control (RBAC):
Description: Permissions are assigned based on predefined roles (e.g., Admin, Editor, Viewer).
Example:
- Admins can manage users, modify data, and view reports.
- Editors can modify content but not manage users.
- Viewers can only view data.

Attribute-Based Access Control (ABAC):
- A user in the "Manager" role can access reports only during business hours.
- A user in "HR" can view employee details only within their department.

#### Horizontal Access Control: Limiting Access to Resources of the Same Type:
Horizontal access control ensures that users can only access their own data or a specific subset of resources. This type is especially critical in multi-user systems where many people access the same type of resource.

```bash
If a vulnerability allows a malicious user to manipulate a request (e.g., changing a user ID in the URL) to view another customer's account details, this would be a horizontal privilege escalation attack.
```

#### Context-dependent access controls:
Context-based access control focuses on specific real-time conditions or the current state of the system. While it uses attributes (like environment conditions), it’s narrower and emphasizes the situation or workflow.

**Real-World Example**:

**E-Commerce Website**

- A user adds items to their cart and proceeds to checkout.
- After payment is completed, the user:
    - **Cannot** modify the cart contents (to prevent fraud or inconsistencies).
    - **Cannot** cancel an order once it is shipped.
    - **Can** only access the order status and shipping details.

| **Access Control Type**                   | **Definition**                                                                             | **Key Features**                                       | **Examples**                                                                                           | **Best Use Case**                                                                    |
| ----------------------------------------- | ------------------------------------------------------------------------------------------ | ------------------------------------------------------ | ------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------ |
| **Discretionary Access Control (DAC)**    | Access is controlled by the resource owner who decides who can access their resources.     | - Ownership-based- Flexible but less secure            | A user can share a file by assigning permissions (e.g., read, write).                                  | Personal systems or smaller environments needing flexibility.                        |
| **Mandatory Access Control (MAC)**        | Access is controlled by a central authority based on security labels and clearance levels. | - Strict and hierarchical- Policies enforced centrally | Access to classified documents based on government security clearance levels (e.g., Top Secret).       | Environments with high-security needs like military or government systems.           |
| **Role-Based Access Control (RBAC)**      | Access is assigned based on predefined roles and responsibilities.                         | - Role-centric- Easy to manage- Hierarchical roles     | Managers can access team reports, but regular employees cannot.                                        | Organizations with clear role structures, like enterprises.                          |
| **Attribute-Based Access Control (ABAC)** | Access decisions are based on attributes of the user, resource, action, or environment.    | - Highly flexible- Policy-driven- Dynamic decisions    | A doctor can access medical records **only if** they’re on the hospital network and during work hours. | Cloud-based systems or environments requiring fine-grained, dynamic access controls. |
| **Horizontal Access Control**             | Restricts access to resources based on user ownership or identity.                         | - User-specific- Prevents data leaks                   | A user can only view their own bank account, not another user’s account.                               | Systems with multiple users managing personal data (e.g., banking, social media).    |
| **Vertical Access Control**               | Restricts access based on the user’s role or privilege level.                              | - Role-dependent- Implements least privilege           | Admins can delete accounts, but regular users cannot.                                                  | Role-driven applications like corporate systems or CMS (Content Management Systems). |
| **Context-Based Access Control**          | Restricts access based on the current state of the application or workflow.                | - Workflow-dependent- State-aware- Dynamic             | A user can edit an order before payment but not after payment is completed.                            | Applications with logical workflows (e.g., e-commerce, ticketing).                   |
| **Rule-Based Access Control**             | Uses a set of predefined rules to grant or deny access.                                    | - Simple- Rule-specific- Configurable                  | Firewalls: Allow access to a server only from specific IP addresses or time periods.                   | Network security or systems requiring simple, static rules.                          |

##### Unprotected functionality :
- looking in robots.txt
- Directory brute forcing using wordlist.
- Look in source code to find interesting directory.

```bash
https://insecure-website.com/administrator-panel-yb556

subfinder | httpx -status-code -silent > live.txt
whatweb -i live.txt | grep -i "admin\|dashboard\|login"
```

Parameter-based access control methods :
1. Storing Role in a Hidden Field (HTML Form)
```bash
<form action="/perform-action" method="POST">
    <input type="hidden" name="user_role" value="admin"> <!-- Hidden field -->
    <button type="submit">Perform Action</button>
</form>

```

2. Storing Role in a Cookie
The user's role is stored in a cookie and sent with every HTTP request.

3. Storing Role in a Query String Parameter
The user's role is passed as a query string parameter in the URL.

https://example.com/dashboard?user_role=admin

- **Hidden Fields**:
    Users can modify HTML forms in the browser using developer tools.
- **Cookies**:
    Cookies can be modified unless they are signed or encrypted.
- **Query Strings**:
    Query parameters are visible, modifiable, and easy to exploit.


Preventation :

- **Server-Side Validation**:
    Never rely on client-provided data (hidden fields, cookies, or query strings) for sensitive access control decisions. Validate roles and permissions server-side using a secure source (like a database).
    
- **Token-Based Authentication**:

    Use secure tokens (e.g., JWTs) to store user roles, but always verify the token's signature and claims on the server.
    
- **Encryption and Signing**:
    
    If you must use client-stored parameters (e.g., in cookies), sign them with a secret key to prevent tampering.

> [!NOTE] Always check
> Always check for roleid , userid or more interseting parameter when exploring the feature. like json request or other.


##### Understanding X-Original-URL and X-Rewrite-URL HTTP Headers :
A client sends a request:
```bash
GET /user HTTP/1.1
Host: example.com
```

​
The reverse proxy rewrites the URL to:
```bash
GET /backend/user HTTP/1.1
Host: example.com
X-Original-URL: /user
```

​
The backend server can process the request and also access the original URL (/user) using the X-Original-URL header.

Risk:
If the backend server trusts the X-Original-URL header without validation, an attacker can send a custom header like:
```bash
GET / HTTP/1.1
Host: example.com
X-Original-URL: /admin/deleteUser
```


This bypasses access control rules based on URLs.

##### X-Rewrite-URL Header:
Purpose: Similar to X-Original-URL, this header is used in some setups to pass the rewritten URL from the reverse proxy or middleware to the backend server.

A client requests an old page:
```bash
GET /old-page HTTP/1.1
Host: example.co
```

The reverse proxy rewrites the URL:
```bash
GET /new-page HTTP/1.1
Host: example.com
X-Rewrite-URL: /new-page
```

The backend server uses the rewritten URL (/new-page) to process the request.
Risk:
If the application blindly trusts the X-Rewrite-URL header, an attacker could craft a malicious request:

Let’s say the application normally blocks this request:
```bash
http
Copy code
POST /admin/deleteUser HTTP/1.1
Host: example.com
```

But the attacker sends this instead:
```bash
http
Copy code
POST / HTTP/1.1
Host: example.com
X-Original-URL: /admin/deleteUser
```

##### HTTP Method-Based Access Control Bypass :
An application allows only POST requests to delete a user, and managers are blocked from making POST requests to /admin/deleteUser.
```bash
POST /admin/deleteUser HTTP/1.1
Host: example.com
```

```bash
GET /admin/deleteUser?userId=123 HTTP/1.1
Host: example.com
```

If the backend server improperly handles the GET request (e.g., it processes the deletion), the attacker can successfully delete the user despite the POST restriction.

##### Horizontal Privilege Escalation
Horizontal privilege escalation happens when a user can access another user's data or resources of the same type, even though they’re only supposed to access their own.

##### What is an Insecure Direct Object Reference (IDOR)
1. **Insecure**: Something is not secure or improperly protected.
2. **Direct Object Reference**: Refers directly to an **object** (a resource like a file, account, or database record) using an **identifier** (e.g., a user ID, file name, or database key).

An IDOR vulnerability happens when an application allows users to directly reference resources (like files or user data) using identifiers, but doesn’t check if the user is authorized to access those resources.

```bash
https://examplebank.com/transactions?account=123
```
123 is your account ID.


If the application doesn’t check that you own account 123, you might be able to modify the URL like this:
```bash
https://examplebank.com/transactions?account=123
https://examplebank.com/transactions?account=124
```


##### What’s Going On with Referer Header-Based Access Controls?
Some websites try to control access to sensitive pages (like deleting a user) by checking the Referer header in the HTTP request. The Referer header is sent by the browser and tells the server which page the user came from.
```bash

POST /admin/deleteUser HTTP/1.1
Referer: https://example.com/admin
```

The server sees the forged Referer and allows the request, even though the attacker never accessed /admin legitimately