🛒 Cartify – PHP E-Commerce Platform

Cartify is a simple and colorful E-Commerce web application built using PHP, MySQL, HTML, CSS, and JavaScript. It allows users to browse products, manage their cart, place orders, and track deliveries, while admins can manage products, categories, and customer orders.

🚀 Features
👤 User Features

Register and Login (with session management)

Browse products by categories

Add/remove products from cart

Place orders with address management

View past orders and status

🛠️ Admin Features

Manage products (CRUD operations)

Manage categories (Smartphones, Laptops, Headphones, etc.)

View and manage customer orders

Handle stock updates

🗄️ Database Structure
Tables
1. users

id (PK)

name, email, password

created_at

2. categories

id (PK)

name

3. products

id (PK)

name, description, price, stock

category_id (FK → categories.id)

created_at

4. addresses

id (PK)

user_id (FK → users.id)

address_line1, address_line2, city, state, postal_code, country

created_at

5. orders

id (PK)

user_id (FK → users.id)

address_id (FK → addresses.id)

status (Pending, Shipped, Delivered)

created_at

6. order_items

id (PK)

order_id (FK → orders.id)

product_id (FK → products.id)

quantity

⚙️ Installation
1️⃣ Clone Repository
git clone https://github.com/your-username/cartify.git
cd cartify

2️⃣ Setup Database

Create a MySQL database:

CREATE DATABASE cartify;


Import tables:

USE cartify;

-- Categories
INSERT INTO categories (id, name) VALUES
(1, 'Smartphones'),
(2, 'Laptops'),
(3, 'Headphones');

-- Sample Products
INSERT INTO products (name, description, price, stock, category_id, created_at) VALUES
('iPhone 15', 'Latest Apple iPhone', 999.99, 10, 1, NOW()),
('Samsung Galaxy S24', 'Newest Samsung flagship phone', 899.99, 15, 1, NOW()),
('MacBook Air M3', 'Apple MacBook Air with M3 chip', 1299.99, 5, 2, NOW()),
('Sony Headphones WH-1000XM5', 'Noise cancelling headphones', 349.99, 20, 3, NOW());

3️⃣ Configure Database Connection

Update config.php with your MySQL credentials:

<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cartify";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

4️⃣ Run Project

Place project folder in htdocs (XAMPP) or www (WAMP).

Start Apache and MySQL in XAMPP.

Open in browser:

http://localhost/cartify

🛠️ Tech Stack

Frontend: HTML, CSS, JavaScript

Backend: PHP

Database: MySQL
Server: Apache (XAMPP/WAMP)
👩‍💻 Author

Developed by Archana 
