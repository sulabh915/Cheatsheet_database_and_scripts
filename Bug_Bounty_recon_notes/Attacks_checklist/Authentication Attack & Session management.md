
Authentication is the process of verifying the identity of a user or client. Websites are potentially exposed to anyone who is connected to the internet.
- Something you know like password or security question or knowledge factors
- Something you have , physical object like mobile phone or security token. “Possession factors”
- Something you are. like biometric or pattern of behavior “inherence factors.”


##  Checklists :
- Enter valid username but an incorrect password login page error says like “only password is incorrect but username is not” or username is already taken when register new user in application.
- Username enumertion via error messages (used regex in burpsuite to negative search for name bases error. via different response like . there is slightly difference between correct and incorrect username in response)
- Username enumeration via response times(check if the username is valid , how much time it takes to process password for given length,if the username is incorrect what is the response time for incorrect user. )first check valid username timing than timing of the password if the username is correct
- Username enumeration via account lockout. brute force each user with 5 null password payload if there is any valid account it will response account lockout or something error message. Brute force password on based on given username. 
- you might sometimes find that your IP is blocked if you fail to log in too many times. In some implementations, the counter for the number of failed attempts resets if the IP owner logs in successfully. This means an attacker would simply have to log in to their own account every few attempts to prevent this limit from ever being reached

list of all possilbe way to bypass rate limiting restriction with following headers:
```bash
X-Forwarded-For: 127.0.0.1        
# Trusted by proxies/load balancers
X-Real-IP: 127.0.0.1              
# Common in NGINX setups
X-Client-IP: 127.0.0.1            
# Used for rate limiting/tracking
X-Remote-IP: 127.0.0.1            
# May influence backend logic
X-Remote-Addr: 127.0.0.1          
# Tries to override remote IP
True-Client-IP: 127.0.0.1         
# Used by CDNs (e.g. Akamai)
CF-Connecting-IP: 127.0.0.1       
# Cloudflare real IP header
Fastly-Client-IP: 127.0.0.1       
# Fastly CDN client IP
X-Cluster-Client-IP: 127.0.0.1    
# Seen in clustered environments
Forwarded: for=127.0.0.1          
# RFC standard version of XFF
X-Originating-IP: 127.0.0.1       
# Used by mail servers & legacy apps
X-Forwarded-Host: 127.0.0.1       
# Can affect virtual host routing
X-Forwarded-Server: 127.0.0.1     
# Backend routing logic
X-Real-Hostname: localhost        
# Tries to spoof internal host
Via: 127.0.0.1                    
# May appear in proxy chains
Forwarded-For: 127.0.0.1          
# Non-standard but seen in wild
Proxy-Client-IP: 127.0.0.1        
# Java-based servers (Tomcat)
WL-Proxy-Client-IP: 127.0.0.1     
# WebLogic-specific heade
```

- If the request in json format we can use array of password in json format . so we can do multiple credential per request.

- IP address-based rate limiting
- Email-based rate limiting
- Device or Session-Based rate limiting
- Geo-Based rate limiting
- Endpoint-specific rate limiting, etc.

### Bypass 2FA
```bash
Log in to your own account. Your 2FA verification code will be sent to you by email. Click the Email client button to access your emails.
Go to your account page and make a note of the URL.
Log out of your account.
Log in using the victim's credentials.
When prompted for the verification code, manually change the URL to navigate to /my-account. The lab is solved when the page loads




Go to the Sign-Up Page:
Visit the registration page at 👉 hxxps://website.com/signup/.
Enter Your Details:
Fill in the form with your email (e.g., myemail@gmail.com) and click the Sign Up button.
OTP Verification Page Appears:
A code will be sent to your email. Do not use this code! Instead, press the Back button.
Re-enter Details with Victim’s Email:
Go back to the Sign-Up page and fill in the form again, but this time use the victim’s email (e.g., victim@gmail.com).
Click Sign Up Again:
When prompted for the OTP, enter the code sent to your email (myemail@gmail.com).
Account Created Successfully:
The website accepts the code, and the account is successfully created using the victim’s email (victim@gmail.com).
```


### Using Burpsuite Advance feature 
```bash
With Burp running, log in as carlos and investigate the 2FA verification process. Notice that if you enter the wrong code twice, you will be logged out again. 

You need to use Burp's session handling features to log back in automatically before sending each request.
In Burp, click Settings to open the Settings dialog, then click Sessions. In the Session Handling Rules panel, click Add. The Session handling rule editor dialog opens.
In the dialog, go to the Scope tab. Under URL Scope, select the option Include all URLs.
Go back to the Details tab and under Rule Actions, click Add > Run a macro.

Under Select macro click Add to open the Macro Recorder. Select the following 3 requests:
GET /login
POST /login
GET /login2

Then click OK. The Macro Editor dialog opens.
Click Test macro and check that the final response contains the page asking you to provide the 4-digit security code. This confirms that the macro is working correctly.
Keep clicking OK to close the various dialogs until you get back to the main Burp window. The macro will now automatically log you back in as Carlos before each request is sent by Burp Intruder.
Send the POST /login2 request to Burp Intruder.
In Burp Intruder, add a payload position to the mfa-code parameter.
In the Payloads side panel, select the Numbers payload type. Enter the range 0 - 9999 and set the step to 1. Set the min/max integer digits to 4 and max fraction digits to 0. This will create a payload for every possible 4-digit integer.
Click on Resource pool to open the Resource pool side panel. Add the attack to a resource pool with the Maximum concurrent requests set to 1.
Start the attack. Eventually, one of the requests will return a 302 status code. Right-click on this request and select Show response in browser. Copy the URL and load it in the browser.
Click My account to solve the lab.

```


### Testing for password reset vulnerability :

**Identify Password Reset Workflow**:

- Locate the "Forgot your password?" feature on the target website.
- Familiarize yourself with the expected behavior of the reset process.

Trigger a password reset request using your own account observer in burpsuite.

- The **reset token** provided (e.g., in a URL query parameter or request body).
- The email format and whether it includes sensitive information.

Analyze the Reset Token in the Email

- Identify the reset token in the URL (e.g., `temp-forgot-password-token`).
- Check:
    - **Token visibility**: Is the token exposed in the URL query parameter?
    - **Token entropy**: Is the token long and random (high entropy)?
    - **Token reuse**: Does the token expire after being used?

Reset Your Password

- Click the reset link in the email and reset your password to a new value.
- Capture the **POST request** in **Burp Proxy** when submitting the new password.
- Observe:
    - Whether the reset token is included in the request.
    - If other parameters (e.g., `username`) are present as hidden inputs.
    - Server response indicating a successful reset.

Test Token Validation on Password Submission

- Send the **POST password reset request** to **Burp Repeater**.
- Modify and test the request:
    - **Delete the reset token** from both the URL and request body.
    - Resubmit the request.
- Check:
    - Whether the password reset still succeeds.
    - If the server validates the token at this step.

Exploit the Lack of Token Validation

- Trigger another password reset request to generate a fresh token.
- Capture and send the **POST request** for resetting the password to **Burp Repeater**.
- Modify the following in the request:
    - **Delete the reset token** value.
    - **Change the username parameter** to another user (e.g., `carlos`).
    - Set a new password for the target user.
- Send the modified request and observe the response:
    - Does the server allow resetting another user's password?

Recommendations:

- Enforce token validation during all stages of the password reset workflow.
- Use high-entropy tokens with strict expiration policies.
- Avoid exposing sensitive parameters like `username` in hidden inputs.


### Password reset poisoning via middleware (token stealing):

Step by Step approach:

- Click on forget password functionality capture the request
- add the `X-Forwarded-Host` header with the URL of your exploit server. For example:

```makefile
X-Forwarded-Host: YOUR-EXPLOIT-SERVER-ID.exploit-server.net
```

- This forces the password reset link to be generated with the exploit server's URL instead of the legitimate domain.

- Visit your exploit server’s logs. Look for an entry similar to:
    
    ```bash
    bash
    Copy code
    GET /forgot-password?temp-forgot-password-token=VALID-TOKEN-FOR-CARLOS
    ```
    
- Extract the `temp-forgot-password-token` parameter value from the request.

**Modify the Reset Link**

- Obtain the password reset link from the victim's email (or the application UI).
    - Example link:
        ```perl
  
        https://target-website.com/reset?temp-forgot-password-token=ATTACKER-TOKEN
        ```
        
- Replace the `temp-forgot-password-token` value with the stolen token:
    
    ```perl
    perl
    Copy code
    https://target-website.com/reset?temp-forgot-password-token=VALID-TOKEN-FOR-CARLOS
    
    ```
    
- Replace our token with carlos token in actual real password reset link vulnerability.



### Prevention
```bash
1.Protect User Credentials
Enforce HTTPS: Redirect all HTTP requests to HTTPS to prevent credentials from being transmitted over unencrypted connections.
Audit Exposure: Regularly check your website for unintentional exposure of usernames or email addresses in public profiles or HTTP responses.

2. Minimize Reliance on Users for Security
Implement Password Checkers: Use tools like zxcvbn to encourage strong passwords with real-time feedback.
Avoid Traditional Password Policies: Move away from rigid rules that result in predictable password patterns (e.g., Password123!).

3. Prevent Username Enumeration
Generic Error Messages: Return identical, non-specific error messages for invalid login attempts regardless of the scenario.
Standardized Responses: Ensure HTTP status codes and response times are indistinguishable for both successful and unsuccessful login attempts.

4. Implement Brute-Force Protection
Rate Limiting: Limit login attempts by IP address and prevent attackers from spoofing their IPs.
CAPTCHA Challenges: Introduce CAPTCHA tests after a threshold of failed login attempts to slow down attackers.
Tedious Processes: Make brute-forcing difficult and time-consuming to discourage attackers.

5. Verify Authentication Logic
Thorough Audits: Review all authentication-related logic for flaws or bypasses.
Robust Validation: Ensure that every check in the verification flow is secure and cannot be bypassed.

6. Secure Supplementary Authentication Features
Password Reset Security: Treat password reset and change functionalities with the same level of scrutiny as the main login mechanism.
Test Account Scenarios: Check for vulnerabilities that attackers might exploit after registering their accounts.

7. Implement Strong Multi-Factor Authentication (MFA)
Avoid Weak MFA: Do not rely on email-based verification as true MFA; it’s an extension of single-factor authentication.
Use Secure 2FA Tools: Opt for dedicated devices or apps (e.g., Google Authenticator) for generating one-time codes.
Avoid SMS Where Possible: While SMS-based 2FA is better than nothing, it is vulnerable to attacks like SIM swapping.
Validate 2FA Logic: Ensure the logic behind MFA checks is robust and cannot be bypassed.
```


## Session management :
### Old Session Does Not Expire After Password Change

Steps to Reproduce:
- Create an account on the target site.
- Log in to the account on two different browsers (e.g., Chrome and Firefox/Incognito).
- On Chrome, navigate to settings and change your password.
- Once the password change is successful, go to the Firefox window (where the old session is active) and refresh the page or navigate to a new tab.

If you remain logged in on Firefox, this is a bug.
Impact: If an attacker has hijacked a user's session, they will retain access to the account even after the victim notices suspicious activity and changes their password to secure it.


### Failure to Invalidate Session on Logout (Persistent Session)

Steps to Reproduce:
- Log in to your account.
- Open a cookie editor extension (e.g., EditThisCookie) and copy all current session cookies to your clipboard.
- Click the "Logout" button on the website.
- Open the cookie editor again and paste the previously copied cookies back into the browser.
- Refresh the page
- If you are logged in again without entering credentials, the bug exists.

Impact: If an attacker steals a victim's cookies (via XSS or network sniffing), they can use them to access the account indefinitely, even if the user frequently logs out.

#### Browser Cache Weakness (Back Button Vulnerability)


Steps to Reproduce:
- Log in to the application.
- Navigate to sensitive pages (Profile, Settings, Payments).
- Log out of the account.
- Press the browser's "Back" button (or Alt + Left Arrow).
- If you can view the sensitive pages or the cached session appears active, report it.

Impact: In public environments (libraries, internet cafes), a malicious user can view the previous user's private data simply by clicking the back button after the victim leaves.


####  Email Verification Bypass (Logic Flaw)

Steps to Reproduce:
- Create an account and receive the initial email verification link. Do not click it yet.
- Log in and change your email address to "Email B"
- The system sends a new link to Email B. Verify that link.
- Go back to settings and change your email back to the original "Email A".
- If "Email A" is now marked as verified without you clicking its specific link, this is a bypass.

Impact: An attacker can sign up with a fake or targeted email address (e.g., admin@company.com)
and verify it without actually owning that email account, potentially bypassing domain-based restrictions.

#### Email Verification Swap Attack

Steps to Reproduce:
- Create an account with "Email A" (Attacker's email).
- Receive the verification link at "Email A" but do not click it
- In the application settings, change your email to "Email B" (Victim's email)
- Go to the inbox of "Email A" and click the verification link sent in Step 2.
- If "Email B" (the victim's email) gets verified using the link meant for "Email A," this is a bug.

Impact: Allows an attacker to confirm an email address they do not own, which can lead to "Pre-Account Takeover" or harassing victims with confirmed account notifications.


#### 6. Password Reset Token Persistence

Steps to Reproduce:
- Create an account with a valid email.
- Log out and request a "Forgot Password" link (Link 1).
- Without using Link 1, request a second "Forgot Password" link (Link 2).
- Use Link 1 to change the password.
- If Link 1 still works → vulnerability confirmed.

Impact:
Old reset links remain valid
Attacker can reuse previously generated links
Leads to persistent account takeover


#### 7. Password Reset Token Re-use

Steps to Reproduce:
- Request a password reset link.
- Use it to change the password.
- Try using the same link again.
- If it works again → vulnerability exists.

Impact:
Token is not invalidated after use
Attacker can reuse link anytime
Leads to account takeover

#### 8. Lack of Session Validation

Steps to Reproduce:
- Log in and go to Profile.
- Edit a field but don’t save.
- Capture request using Burp Suite
- Log out from browser.
- Replay request in Repeater.
- If it still works → vulnerable.

Impact:
Actions possible after logout
Unauthorized changes to user account
Leads to full account takeover

#### 9. Session Fixation
Steps to Reproduce:
- Visit login page and note session ID.
- Log in with valid credentials.
- Check if session ID changes.
- If same → vulnerable.

Impact:
Attacker can pre-set session ID
Gains access after victim logs in
Leads to session hijacking

#### 10. Concurrent Session Limit Bypass
Steps to Reproduce:
- Log in on Browser A.
- Log in on Browser B.
- Check if Browser A is logged out.
- If not → test multiple logins via Intruder.

Impact:
Multiple active sessions allowed
Hard to detect attacker presence
Weakens fraud detection

#### 11. Missing Session Rotation After Privilege Change
Steps to Reproduce:
- Log in as normal user
- Note session ID
- Perform privilege upgrade action
- Check session ID again
- If unchanged → vulnerable

Impact:
Attacker with old session gains elevated access
No re-authentication required
Leads to privilege escalation

 
 ####  12. Unrestricted Session Duration
Steps to Reproduce:
- Log in
- Capture session cookie
- Wait hours/days
- Reuse cookie
- If still valid → vulnerable

Impact:
Sessions never expire
Stolen cookies usable long-term
Leads to persistent unauthorized access

#### 13. Weak "Remember Me" Token
Steps to Reproduce:
- Log in with “Remember Me”
- Capture cookie
- Log out
- Reuse cookie
- If login works → vulnerable

Impact:
Token is static / reusable
Works even after logout
Leads to persistent account access

#### 14. JWT Misconfiguration
Steps to Reproduce:
- Log in and capture JWT
- Log out
- Reuse JWT in request
- If still valid → vulnerable

Impact:
Tokens not revoked
Acts as permanent access key
Leads to account takeover



