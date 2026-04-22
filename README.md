# ecommerce-web-application-final-year

Full-stack e-commerce web application featuring user authentication, product management, shopping cart, checkout system, admin dashboard, and database integration

---

## Installation and Setup Guide

## Login Credentials
### Administrator
•	Username: admin 
•	Email: admin@example.com 
•	Password: admin123 
•	Role: administrator 
### Vendor
•	Username: vendor 
•	Email: vendor@example.com 
•	Password: vendor123 
•	Role: vendor 
### Visitor 
•	Username: visitor 
•	Email: visitor@example.com 
•	Password: visitor123 
•	Role: visitor 
### Customer 
•	Username: customer 
•	Email: customer@example.com 
•	Password: customer123 
•	Role: customer
## Email Configuration (PHPMailer)
Only required if email functionality is not working on the support and confirmation pages: 
Files that require configuration:
•	lokimart/logic/send_order_email.php
•	lokimart/logic/support_logic.php
Steps to configure email settings:
1.	Open the files listed above and locate the PHPMailer section.
2.	Replace the default email with your own:
•	mail->Username = 'your-email@gmail.com';
•	$mail->setFrom('your-email@gmail.com', 'LokiMart');
3.	Generate a Google App Password and replace the example password:
1.	Google requires an App Password when using PHPMailer with Gmail (2-Step Verification must be enabled).
2.	Steps:
•	Visit: https://myaccount.google.com/apppasswords
•	Generate a new 16-character App Password
•	Replace the password in the PHPMailer code:
o	$mail->Password = 'your-app-password';
Replace your-email@gmail.com and your-app-password with your own details.
Note: This project uses demo credentials for testing purposes.

