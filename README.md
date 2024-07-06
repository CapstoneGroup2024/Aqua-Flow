# Aqua Flow: Water Refilling Station Management Website

## Overview

Aqua Flow is a solution for managing a water distribution business. This platform enables users to manage customer orders, track water deliveries, maintain inventory, and monitor the overall performance of the water station. 

## Features

- **User Management**: Add, edit, and delete user information.
- **Order Management**: Place new orders, track ongoing orders, and review order history.
- **Delivery Management**: Assign delivery tasks to drivers and track delivery status.
- **Inventory Management**: Monitor stock levels of water containers and other supplies.
- **Reporting and Analytics**: Generate reports on sales, deliveries, and inventory usage.
- **User Authentication**: Secure login for admin and staff users.
- **Email Notifications**: Send order confirmations, delivery updates, and other notifications via email.

## Technology Stack

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Server Environment**: XAMPP (includes Apache HTTP Server, MySQL database, PHP, phpMyAdmin)
- **Frameworks**: 
  - **Frontend Framework**: Bootstrap
  - **Backend Framework**: Laravel
- **Email Service**: PHPMailer
- **Version Control**: Git

For installing Aqua Flow using XAMPP, you'll import the `aquaflowdb.sql` file into your MySQL database. Here’s a simplified guide for installation:

### Installation Steps

#### Prerequisites
- XAMPP installed (includes Apache, MySQL, PHP)
- Composer installed (for PHP dependencies)

#### Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/CapstoneGroup2024/Aqua-Flow.git
   cd Aqua-Flow
   ```

2. **Import Database**
   - Start XAMPP and ensure Apache and MySQL are running.
   - Open phpMyAdmin by visiting `http://localhost/phpmyadmin` in your browser.
   - Create a new database named `aquaflowdb`.
   - Import `aquaflowdb.sql` into the `aquaflowdb` database:
     - Click on the `aquaflowdb` database in phpMyAdmin.
     - Click on the `Import` tab.
     - Choose the `aquaflowdb.sql` file and click `Go`.

3. **Install Dependencies**
   - Open a terminal in the project root directory.
   - Install PHPMailer dependency using Composer:
     ```bash
     composer require phpmailer/phpmailer
     ```

## Usage

1. **Admin Login**

   - Navigate to the login page.
   - Enter admin credentials to access the admin dashboard.

2. **Managing Users**

   - Use the 'Users' section to add, edit, or delete user information.

3. **Placing and Tracking Orders**

   - Navigate to the 'Orders' section to place new orders or track existing ones.
   - Order confirmations and updates are automatically sent via email using PHPMailer.

4. **Managing Deliveries**

   - Update delivery statuses.
   - Delivery updates are sent via email to customers.

5. **Inventory Management**

   - Monitor stock levels and update inventory records as needed.

6. **Reporting**

   - Generate and view reports on sales, deliveries, and inventory.
