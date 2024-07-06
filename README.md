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
- **Frameworks**: 
  - **Frontend Framework**: Bootstrap
  - **Backend Framework**: Laravel
- **Email Service**: PHPMailer
- **Version Control**: Git

## Installation

### Prerequisites

- Web server (e.g., Apache)
- PHP (>= 7.4)
- MySQL database
- Composer

### Steps

1. **Clone the Repository**

   ```bash
   git clone https://github.com/yourusername/water-station-management.git
   cd water-station-management
   ```

2. **Install Dependencies**

   ```bash
   composer install
   npm install
   ```

3. **Setup Environment Variables**

   Rename `.env.example` to `.env` and configure your database settings, email server settings, and other environment variables.

4. **Run Database Migrations**

   ```bash
   php artisan migrate
   ```

5. **Start the Server**

   ```bash
   php artisan serve
   ```

   Ensure your web server is running and navigate to `http://localhost:8000` in your browser.

## Email Configuration

1. **Install PHPMailer**

   PHPMailer is included as a dependency in the composer.json file. Ensure it is installed by running `composer install`.

2. **Configure Email Settings**

   In the `.env` file, add your email server configuration:

   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.example.com
   MAIL_PORT=587
   MAIL_USERNAME=your_email@example.com
   MAIL_PASSWORD=your_email_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=your_email@example.com
   MAIL_FROM_NAME="${APP_NAME}"
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
