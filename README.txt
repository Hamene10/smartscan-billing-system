QR Code Based Grocery Billing System
====================================

HOW TO RUN THE PROJECT
----------------------

1. Software Requirements:
   - Install XAMPP or WAMP Server.
   - Any Web Browser (Chrome recommended).

2. Installation Steps:
   - Copy the 'smart_scan_billing' folder to your server directory:
     - XAMPP: C:\xampp\htdocs\
     - WAMP: C:\wamp\www\
   
   - Start Apache and MySQL from XAMPP/WAMP Control Panel.

3. Database Setup:
   - Open Browser and go to: http://localhost/phpmyadmin/
   - Create a new database named 'smart_scan_billing'.
   - Click on 'Import' tab.
   - Choose the file 'database.sql' from inside the project folder.
   - Click 'Go' to import the tables.

4. Run the Project:
   - Open Browser and visit: http://localhost/smart_scan_billing/

5. Login Cerdentials:
   - Admin Login:
     - Username: admin
     - Password: admin123  (or create new via database insert if needed)
   
   - User Login:
     - Register a new user from the registration page.

FEATURES
--------
1. Admin Panel:
   - Manage Products (Add/Edit/Delete/QR Gen)
   - View Low Stock & Expiry Alerts
   - View Sales Reports

2. User Panel:
   - "Scan" QR Codes (Enter Product Code from generated list)
   - Add to Cart
   - Fast Billing & Invoice Generation

NOTES
-----
- The QR Scan is simulated for web browser environment. Use the Product Codes shown on the "Manage Products" or "Scan" page.
- "PHP QR Code library" logic acts via API for simplicity and reliability in this demo.
