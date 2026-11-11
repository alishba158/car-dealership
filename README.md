# Northline Motor Co. — Car Buy & Sell Website

A small, server-rendered car dealership website built with HTML, CSS, JavaScript, Core PHP, and MySQL/MariaDB. It includes a customer-facing collection and inquiry forms, plus a password-protected admin studio for cars, seller requests, and contact messages.

## Requirements

- Windows 10 or 11
- XAMPP for Windows (includes Apache, PHP, MariaDB, and phpMyAdmin)
- A current browser

The local environment check found XAMPP at `C:\xampp`, including PHP 8.3, MariaDB 10.11, Apache 2.4, and phpMyAdmin. Apache and MySQL were not running during the initial check.

## Install and run with XAMPP

1. If XAMPP is not installed, download the Windows installer from [apachefriends.org](https://www.apachefriends.org/download.html), run it, and install Apache, MySQL (MariaDB), PHP, and phpMyAdmin. The current machine already has the XAMPP components installed at `C:\xampp`.
2. Open **XAMPP Control Panel** and click **Start** beside **Apache** and **MySQL**. Both rows should show as running.
3. Copy the complete `carweb` project folder to `C:\xampp\htdocs\car-dealership`. The project root must contain `index.php`, `config`, `includes`, and `database`.
4. Open [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/), choose **Import**, select `C:\xampp\htdocs\car-dealership\database\car_dealership.sql`, and click **Import**. The script creates the `car_dealership` database and sample inventory. It is safe to import again; sample cars are not duplicated.
5. Open `config\db.php`. The defaults suit a new XAMPP installation (`127.0.0.1`, database `car_dealership`, user `root`, blank password). If you configured a MySQL root password, update `DB_PASS`. The application detects its URL prefix from the folder it runs in.
6. Make sure `uploads\cars` exists and is writable by Apache. The included folder is ready in the project; PHP can also create it when receiving the first upload.
7. Visit [http://localhost/car-dealership/](http://localhost/car-dealership/). To set up the first administrator, visit [http://localhost/car-dealership/admin/setup.php](http://localhost/car-dealership/admin/setup.php), choose your own username and password (at least 12 characters), and submit. The password is stored using PHP `password_hash()`. The setup page becomes unavailable once the first admin exists.
8. Sign in at [http://localhost/car-dealership/admin/login.php](http://localhost/car-dealership/admin/login.php) using the credentials you created. There is intentionally no shared default password.

## What is included

- Home, collection/search/filter, vehicle detail and photo gallery, sell-a-car form, about, and contact pages.
- Responsive layouts and locally stored automotive photos used by the sample inventory.
- PDO prepared statements, form validation, CSRF tokens, escaped output, secure sessions, and password hashing/verification.
- Admin tools to add/edit/delete cars, add gallery photos, feature cars, mark inventory available/sold, review/approve/reject seller requests, and delete contact messages.
- MySQL foreign keys and indexes in `database\car_dealership.sql`.

## Useful checks

- Public site: `http://localhost/car-dealership/`
- Inventory: `http://localhost/car-dealership/buy-cars.php`
- Admin setup (first admin only): `http://localhost/car-dealership/admin/setup.php`
- Admin sign-in: `http://localhost/car-dealership/admin/login.php`

Uploaded images accept JPG, PNG, and WebP files up to 5 MB each. Admin car galleries accept up to eight images per upload. If the site cannot connect to MySQL, confirm the MySQL row is running in XAMPP, then review the host, database name, user, and password in `config\db.php`.

Sample inventory is demonstration content. Replace or remove it in the admin panel before using the website for a real dealership.
