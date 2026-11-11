# Complete Car Buy & Sell Website – Development Prompt

Act as a professional full-stack PHP web developer and UI/UX designer.

Build a complete, functional, small-scale **Car Buy & Sell Website** for a car dealership company. Develop the entire project from scratch, including frontend, backend, admin panel, MySQL database, and local installation setup.

## 1. Technology Stack

Use ONLY:

- HTML5
- CSS3
- JavaScript
- Core PHP (no Laravel)
- MySQL Database
- XAMPP (Apache + PHP + MySQL)
- phpMyAdmin

Do not use React, Node.js, Python, or other backend frameworks.

## 2. Frontend Website

Create a modern, professional, responsive car dealership website.

Required pages:

1. Home Page
2. Buy Cars
3. Car Details
4. Sell Your Car
5. About Us
6. Contact Us

### Homepage
- Professional navigation bar and company logo.
- Attractive hero section with a high-quality car image.
- Search cars by brand and model.
- Featured cars section.
- Latest available cars.
- Sell Your Car promotional section.
- About company section.
- Customer testimonials.
- Professional footer.

### Buy Cars
- Display available cars in attractive cards.
- Show car image, brand, model, year, mileage, and price.
- Search and filter cars.
- View Details button.

### Car Details
- Multiple car images.
- Car specifications.
- Price and description.
- Contact seller through WhatsApp or phone.

### Sell Your Car
Create a form containing:
- Customer name
- Phone number
- Email
- Car brand
- Car model
- Manufacturing year
- Mileage
- Expected price
- Description
- Car image upload

Save submissions in MySQL for admin review.

### Contact Us
- Contact form.
- Company phone and email.
- Address and social media links.

## 3. Simple Admin Panel

Create a separate admin panel at:

/admin/login.php

Admin login must use a username and password stored securely in MySQL.

### Admin Dashboard
Display:
- Total Cars
- Available Cars
- Total Sell Requests
- Total Contact Messages

### Admin Features

**Manage Cars**
- Add new cars.
- Upload car images.
- Edit car information.
- Delete cars.
- Mark cars as Available or Sold.

**Manage Sell Requests**
- View submitted car details.
- View customer information.
- Approve or reject requests.

**Manage Messages**
- View customer contact messages.
- Delete messages.

**Logout**
- Secure admin logout.

Keep the admin panel simple, clean, and easy to operate.

## 4. MySQL Database

Database name: `car_dealership`

Create these tables:

1. admins
2. cars
3. car_images
4. sell_requests
5. messages

Create all primary keys, foreign keys, and appropriate relationships.

Provide a complete SQL file:

database/car_dealership.sql

The SQL file must create the database and all required tables.

Add a default admin account through a secure setup process. Never store plain-text passwords.

## 5. PHP Backend

Implement all functionality using Core PHP.

Requirements:
- MySQL database connection.
- Admin authentication.
- PHP sessions.
- Add, edit, delete, and display car records.
- Save customer sell requests.
- Save contact messages.
- Fetch dynamic car information from MySQL.
- Validate all forms.
- Use PDO prepared statements.
- Use password_hash() and password_verify().
- Protect admin pages from unauthorized access.
- Validate image uploads and restrict file types and sizes.

## 6. UI/UX Design Requirements

The website must look like a professionally developed car dealership website, not an AI-generated template.

Design direction:
- Premium automotive aesthetic.
- Clean and modern layout.
- White, black, and dark grey color palette.
- Elegant typography.
- Professional car photography.
- Well-designed car listing cards.
- Smooth hover effects and subtle animations.
- Consistent spacing and alignment.
- Responsive design for desktop, tablet, and mobile.

Avoid:
- Excessive whitespace.
- Unnecessary animations.
- Oversized typography.
- Misaligned images.
- Generic dashboard designs.
- Unnecessary features.

Use genuine automotive images from freely usable image sources. Do not use broken or placeholder image links.

## 7. Project Folder Structure

Organize the project professionally:

car-dealership/
- index.php
- buy-cars.php
- car-details.php
- sell-car.php
- about.php
- contact.php
- config/db.php
- includes/header.php
- includes/footer.php
- admin/login.php
- admin/dashboard.php
- admin/cars.php
- admin/add-car.php
- admin/edit-car.php
- admin/requests.php
- admin/messages.php
- admin/logout.php
- assets/css/
- assets/js/
- assets/images/
- uploads/cars/
- database/car_dealership.sql
- README.md

You may add necessary helper files while keeping the project organized.

## 8. Installation and Setup

Prepare the entire project for local development on Windows.

Check whether the required software is installed:
- PHP
- MySQL
- Apache
- XAMPP
- phpMyAdmin

If missing, guide me through installing XAMPP, which includes PHP, MySQL/MariaDB, Apache, and phpMyAdmin.

Do not assume software is installed.

The project should run locally through:

http://localhost/car-dealership/

Admin panel:

http://localhost/car-dealership/admin/login.php

Provide clear instructions for:
1. Installing XAMPP if required.
2. Starting Apache and MySQL.
3. Placing the project inside htdocs.
4. Creating/importing the database using phpMyAdmin.
5. Configuring the database connection.
6. Creating the initial admin account.
7. Running and testing the website.

## 9. Important Development Instructions

- Build the complete working project, not just a UI prototype.
- Create actual PHP files, CSS files, JavaScript files, and SQL files.
- Do not leave incomplete buttons or non-functional forms.
- Connect every required frontend feature to the backend.
- Ensure all car listings are dynamically loaded from MySQL.
- Ensure admin changes appear on the frontend.
- Test all CRUD operations.
- Test admin login and logout.
- Fix PHP errors and database connection issues.
- Keep the code beginner-friendly and properly commented.
- Do not stop after creating the homepage.

## 10. Final Deliverables

At completion, provide:

1. Complete working website.
2. Complete PHP backend.
3. Functional admin panel.
4. MySQL database SQL file.
5. Database connection configuration.
6. Admin login credentials created during setup.
7. README installation guide.
8. Instructions to run the project locally.

**Final Goal:** Deliver a fully functional, small-scale Car Buy & Sell Management Website using HTML, CSS, JavaScript, Core PHP, and MySQL, ready to run locally on Windows using XAMPP.