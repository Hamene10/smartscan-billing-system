# QR Code Based Grocery Billing System
**Final Year Academic Project**

## 1. Project Abstract
The "QR Code Based Grocery Billing System" is a web-based application designed to streamline the checkout process in grocery stores. Traditional billing systems often lead to long queues and delays. This project introduces a self-checkout mechanism where customers can scan product QR codes, view product details, add items to a digital cart, and generate a bill automatically. The system also empowers administrators with inventory management features, including real-time stock tracking and expiry date alerts.

## 2. System Modules

### 2.1 Authentication Module
- Secure login for Admin and Users.
- Registration functionality for new customers.
- Session management to protect pages.

### 2.2 Admin Module
- **Dashboard**: Quick view of total products, low stock items, and sales.
- **Product Management**: Add, update, and delete products. System automatically assigns a unique QR Product Code.
- **Inventory Alerts**: Highlights products with low stock (< 5) or nearing expiry (7 days).
- **Sales Reports**: Detailed view of completed orders and revenue.

### 2.3 User Module
- **Dashboard**: Customer landing page.
- **Scan Product**: Interface to input/scan product QR codes to fetch details.
- **Cart Management**: Add items, adjust quantities, or remove items.
- **Purchase History**: View past orders and bills.

### 2.4 Billing Module
- **Checkout**: Confirms the order and reduces stock from the inventory database.
- **Invoice Generation**: Produces a professional, printable bill with order details.

## 3. Database Design
The system uses a relational MySQL database with the following tables:
- `admin`: Stores admin credentials.
- `users`: Stores customer details.
- `products`: key table for inventory (Code, Price, Stock, Expiry).
- `cart`: (Session based in this implementation, optional DB storage).
- `orders`: Stores order summary.
- `order_items`: Maps products to orders (Many-to-Many relationship).

## 4. Technology Stack
- **Frontend**: HTML5, CSS3, Bootstrap 5 (Responsive UI).
- **Backend**: Core PHP.
- **Database**: MySQL (via MySQLi).
- **Server**: Apache (XAMPP).

## 5. Conclusion & Future Scope
This project successfully demonstrates a functional prototype of a modern billing system. It reduces manual entry errors and speeds up the billing process.
**Future Enhancements**:
- Integration with physical barcode scanners via USB.
- Payment Gateway integration (Stripe/PayPal).
- Mobile App version using Flutter/React Native.
