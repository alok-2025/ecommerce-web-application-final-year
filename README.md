# ecommerce-web-application-final-year

Full-stack e-commerce web application featuring user authentication, product management, shopping cart, checkout system, admin dashboard, and database integration

---

## Installation and Setup Guide

## Installation Steps

### Download and Setup Project
- Download or clone the repository  
- Extract the ZIP file  
- Rename the folder to `lokimart` (if needed)  
- Copy the folder into your XAMPP `htdocs` directory  

Final path should be:  
xampp/htdocs/lokimart  

### Move Project to XAMPP Directory  
Copy the extracted folder into:  
xampp/htdocs/  
Example:  
xampp/htdocs/lokimart  

### Start XAMPP Server
Open XAMPP Control Panel and start:  
Apache ✔  
MySQL ✔  

### Create Database  
Open browser  
Go to:  
http://localhost/phpmyadmin  
Click New  
Create database:  
lokimart_db  

### Import Database File  
Open the created database lokimart_db  
Click Import  
Select file:  
lokimart_db.sql  
Click Go  

### Run the Project  
Open browser and go to:  
http://localhost/lokimart  

## Login Credentials

### Administrator
-	Username: admin  
-	Email: admin@example.com 
-	Password: admin123 
-	Role: administrator 

### Vendor
-	Username: vendor 
-	Email: vendor@example.com 
-	Password: vendor123 
-	Role: vendor 

### Visitor 
-	Username: visitor 
-	Email: visitor@example.com 
-	Password: visitor123 
-	Role: visitor 

### Customer 
-	Username: customer 
-	Email: customer@example.com 
-	Password: customer123 
-	Role: customer

## Email Configuration (PHPMailer)  
**Only required if email functionality is not working** on the support and confirmation pages:  
Files that require configuration:  
-	lokimart/logic/send_order_email.php  
-	lokimart/logic/support_logic.php
  
Steps to configure email settings:  

1.	Open the files listed above and locate the PHPMailer section.

2.	Replace the default email with your own:  
-	mail->Username = 'your-email@gmail.com';  
-	$mail->setFrom('your-email@gmail.com', 'LokiMart');  

3.	Generate a Google App Password and replace the example password:  

4.	Google requires an App Password when using PHPMailer with Gmail **(2-Step Verification must be enabled).**  

5.	Steps:  
-	Visit: https://myaccount.google.com/apppasswords  
-	Generate a new 16-character App Password  
-	Replace the password in the PHPMailer code:  
-	$mail->Password = 'your-app-password';  

6. Replace your-email@gmail.com and your-app-password with your own details.

Note: This **project uses demo credentials for testing purposes.**
