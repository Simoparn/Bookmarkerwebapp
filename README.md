## TABLE OF CONTENTS
### Background
### Special thanks
### Operating principle
### Directories
### Installation instructions
### TODO
### Problems

## Background

ENGLISH: Experimental browser bookmarking web application service. Part of Omnia's web programming (Web-ohjelmointi) course (autumn 2022).

Part of the Omnia web programming course (autumn 2022-spring 2023). Server platform is [XAMPP](https://www.apachefriends.org/). Utilizes PHPMailer library and [Twilio Sendgrid](https://app.sendgrid.com/) or [Mailtrap](https://mailtrap.io) services for contact emails and password changes with SMTP, SendGrid can send the email to the real SMTP server. Utilizes [shaarli/shaarli/netscape-bookmark-parse](https://packagist.org/packages/shaarli/netscape-bookmark-parser) and [kafene/netscape-bookmark-parser](https://packagist.org/packages/kafene/netscape-bookmark-parser) packages for netscape bookmark file parsing. Database MySQL/MariaDB. Database handling with mySQLi.

### Special thanks

## Operating principle
### Credentials
.env configuration file must be created to the project root folder for database connection and sending emails,
see the installation instructions section for further information. 
### Navigation
Graphics are rendered through index.php, which is always shown to the user. The graphics are rendered based on require statements and URL link query variables.
The page navigation/user profile panels show the currently selected page with a different background color for the relevant link.
### User management
Page visitors can create a new user profile. Profile usernames must be unique. The application also doesn't allow creating a new
user if the given email already exists in the database. Users are allowed to have the same address (Family members etc.). Passwords are saved to the database in hash format (hashing algorithm bcrypt).
#### Forgotten password change
A message about the forgotten password is sent through the Mailgrid or Twilio Sendgrid service. Twilio Sendgrid can send the email to the real SMTP server in the free version. Password reset form cannot be opened without
the user email and username hashes in the link sent to the email. Even then, the old password is required for resetting the password.
#### Remember me
If the Remember me option is used when logging in, the application creates an authentication token cookie that is also saved to the database.
Expired tokens are deleted from the database if any page is opened while logged off and the opener machine has no active cookie.
### Notifications
#### Notifications for user management
-Error notification if opening the login form is attempted while user has already logged in
-Error notification if opening the forgotten password change link is attempted while logged in
-Error notification if opening the forgotten password change link is attempted without a valid link
#### Notifications for email
-Error notification if attempting to use any email service besides Mailtrap or Sendgrid. 
### Database structure
#### Database structure for user management
Several users can have the same address (family members etc.)

#### Database structure for importing and showing bookmarks in the service
bookmark -> unique URLs, have a human-readable name
tagsofbookmarks -> all folder hierarchies used for bookmarks, described as a string "parentfolder childfolder" etc. This is needed because many users can use identical folderhierarchies
bookmarksofusers -> bookmarks are always related to a certain user and a certain folder

Redundant records are deleted automatically from bookmarksofusers when the relevant records are deleted from tagsofbookmarks and bookmark tables.
## Directories

### index.php
Page graphics are generated and certain event handlers (expired token deletion) are called here.
### Composer
[For PHP dependency management](https://getcomposer.org/)

### Graphics
Renderable graphical components, pages and notifications.
### Eventhandlers
Event handlers (database connection/user registration/user login/feedback email/forgotten password email), 
### Databasetestdata
MySQL Database table schemas and test materials, users must be created independently with INSERT statements.
### Vendor
Downloaded PHP dependencies
#### Environment variables
    vlucas/dotenv
#### Email
    (PHPMailer.php/SMTP.php/Exception.php
    sendgrid-php

#### Netscape bookmark file parsing
    shaarli/netscape-bookmark-parser

#### Testing
    phpunit
#### Logging
    monolog
### styles
Custom CSS styles



## Installation instructions
Make sure that [XAMPP](https://www.apachefriends.org/) is installed and the repository has been cloned to the XAMPP htdocs folder.

After cloning the repository, create the following files inside the Eventhandlers folder: connect_database.php

### Omitted files
Create the .env file inside the project root folder in the following format. MAILSERVICE variable must have 'mailtrap' or 'sendgrid' as a value for emailing to work.

    MAILSERVICE=utilized_email_service_here
    MAILTRAPHOSTDOMAIN=mailtrap_host_SMTP_domain_here
    MAILTRAPHOSTUSERNAME=mailtrap_host_username_here
    MAILTRAPHOSTPASSWORD=mailtrap_salasana_tähän
    SENDGRIDRECEIVERIDENTITY=sendgrid_web_service_receiver_identity_email_here
    SENDGRIDSENDERIDENTITY=sendgrid_web_service_sender_identity_email_here
    SENDGRIDHOSTDOMAIN=sendgrid_web_service_SMTP_domain_here
    SENDGRIDHOSTUSERNAME=sendgrid_user_here
    SENDGRIDHOSTPASSWORD=sendgrid_password_here
    DATABASEUSERNAME=database_username_here
    DATABASEPASSWORD=database_password_here

### Email service configuration

#### Gmail API

#### SMTP, email messages without 2 phase 
Please see the following instructions for explanation.
[Email configuration instructions for SMTP 1](https://netcorecloud.com/tutorials/send-an-email-via-gmail-smtp-server-using-php/)
[Email configuration instructions for SMTP 2](https://phppot.com/php/send-email-in-php-using-gmail-smtp/)

NOTE: Remember to set 2 phase identification and the 'less secure apps' function
back on if an alternative email service configuration can be found for Gmail API.

#### Sendgrid (SMTP)

If Twilio Sendgrid is needed for sending SMTP emails, create a [SendGrid account](https://app.sendgrid.com) and create the necessary credentials: (Integrate->SMTP Relay)


#### Mailtrap (SMTP)

Sandbox->Inboxes->SMTP settings


### connect_database.php
Use the .env file credentials

### send_Feedback_email.php
Use the .env file credentials

### send_forgotten_password_email.php
Use the .env file credentials

### Deployment to Microsoft Azure
[Log in to Azure](https://portal.azure.com/) 
#### Repository settings
Select the main branch for deployment in Azure Deployment Center:
Dashboard -> App Service -> Deployment Center -> Source: Github -> Authorize
#### PHP version configuration
App Service -> Settings -> Configuration -> General settings -> PHP 7.4
#### Sleep mode prevention
Settings -> Configuration -> General settings ->  Always on
#### Database import to Azure
Open the local project's PHPMyAdmin -> Export
Then go to App Service and open: MySQL In App -> Manage, which opens a cloud version PHPMyAdmin to your browser, then select Import




#### Database configuration in Azure
mysql\data\MYSQLCONNSTR_localdb.ini:
    Database=localdb; Data Source=127.0.0.1:portnumber; User id=azure; Password=password
#### File modification in Azuren App Service
Open App Service Editor
#### Command line interface in Azure
Open Advanced tools or Development tools->console

#### Show Azure version's PHP data
https://yourazureusernamehere.azurewebsites.net

#### Check the ready Azure app
Deployment center -> Browse
### MYSQL database local configuration
Configuration ->

Open PHPMyAdmin and execute the following SQL command: SET PASSWORD FOR 'root'@'localhost' = PASSWORD ('yourdatabasepasswordhere')
Configure the password after this to the config.inc.php file (XAMPP Control Panel -> Apache -> config), so that PHPMyAdmin can be used afterwards.

Create test users by adding them in the following format format:
    INSERT INTO userprofile VALUES('usernamehere','passwordhashhere','firstnamehere','surnamehere','phonenumberhere','emailhere',address_id,user_active_boolean_here,is_user_staff_boolean_here,'2022-01-01 12:00:00',NULL)

    Trouble with self-created test hashes (password_hash()), it is adviced to create and copy test hashes with the following: https://bcrypt.online/

### Server file upload configuration
[See also PHP manual instructions for file handling configuration](https://www.php.net/manual/en/features.file-upload.common-pitfalls.php)
Make sure that the PHP server allows uploading of bookmarks files -> XAMPP Control Panel -> Apache -> config -> php.ini -> file_uploads = On
Also make sure that the PHP server allows uploading of sufficiently large files -> XAMPP Control Panel -> Apache -> config -> php.ini -> upload_max_filesize=40M
## TODO

### navigation and navigation panel modularization 
    -Is it possible to remove all notifications out of index.php without breaking the app?

### User_management
    -Remember me (authentication-token/cookie for automatic login even if the browser is opened and reopened) ->
        -still unable to show error message, when failing to automatically login with remember ,e
        -If the cookie is has been saved to the computer, the app still logs in automatically after reopening the browser, even if the user logged off during the last session.
         
        

### Notifications
    -Notification if attempting to open login form and user has already logged in -> Doesn't show the error message if already logged in
    and the authentication token cookie is still active.
    
### Email sending

### Database
    -SQL injection prevention (Prepared statements) -> DONE
### Assignment 22.09.2022: 
    email sending to admin with feedback form
            Mailtrap->tested for feedback

### Assignment 23.09.2022: 
    Try also sending email with SendGrid-> Tested for feedback

### Assignment 26.09.2022:
    Connect the Github main branch to Azure, export the local dtabase to Azure and retrieve the credentials with the $_SERVER superglobal in a specialized file
    while in cloud.


    


## Problems
Gmail API: 
Google Cloud->APIs and Services->Credentials->Create Oauth Client Id-> Doesn't accept localhost: Invalid Redirect: must contain a domain. 

Gmail SMTP: 
    Less Secure Apps option removed? https://myaccount.google.com/lesssecureapp https://support.google.com/accounts/answer/6010255

Sendgrid SMTP Relay:
Next error with the PHPMailer library, [SendGrid package](https://docs.sendgrid.com/for-developers/sending-email/quickstart-php) mandatory? Setting full rights on didn't help:

    SMTP connect() failed. https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting
    2022-09-23 21:59:14 SMTP ERROR: Failed to connect to server: Yhteyden muodostamisyritys epäonnistui, koska vastapuoli ei vastannut oikein määritetyn ajan kuluessa, tai aiemmin muodostettu yhteys epäonnistui, koska palvelin, johon yritettiin muodostaa yhteys, ei vastannut (10060)

Following error with [SendGrid](https://github.com/sendgrid/sendgrid-php) package when using SMTP Relay credentials:

    403 Array ( [0] => HTTP/1.1 403 Forbidden [1] => Server: nginx [2] => Date: Fri, 23 Sep 2022 23:32:45 GMT [3] => Content-Type: application/json [4] => Content-Length: 281 [5] => Connection: keep-alive [6] => Access-Control-Allow-Origin: https://sendgrid.api-docs.io [7] => Access-Control-Allow-Methods: POST [8] => Access-Control-Allow-Headers: Authorization, Content-Type, On-behalf-of, x-sg-elas-acl [9] => Access-Control-Max-Age: 600 [10] => X-No-CORS-Reason: https://sendgrid.com/docs/Classroom/Basics/API/cors.html [11] => Strict-Transport-Security: max-age=600; includeSubDomains [12] => [13] => ) {"errors":[{"message":"The from address does not match a verified Sender Identity. Mail cannot be sent until this error is resolved. Visit https://sendgrid.com/docs/for-developers/sending-email/sender-identity/ to see the Sender Identity requirements","field":"from","help":null}]}

Sendgrid API:

    Sendgrid Email API requires either PHP-version 5.6 or 7.0 (24.09.2022)?

    