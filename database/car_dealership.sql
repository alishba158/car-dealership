CREATE DATABASE IF NOT EXISTS car_dealership
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE car_dealership;

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(40) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_admins_username (username)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cars (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  brand VARCHAR(80) NOT NULL,
  model VARCHAR(100) NOT NULL,
  year SMALLINT UNSIGNED NOT NULL,
  mileage INT UNSIGNED NOT NULL DEFAULT 0,
  price DECIMAL(12,2) NOT NULL,
  description TEXT NOT NULL,
  body_type VARCHAR(50) NOT NULL DEFAULT '',
  transmission VARCHAR(50) NOT NULL DEFAULT '',
  fuel_type VARCHAR(50) NOT NULL DEFAULT '',
  color VARCHAR(50) NOT NULL DEFAULT '',
  status ENUM('available','sold') NOT NULL DEFAULT 'available',
  featured TINYINT(1) NOT NULL DEFAULT 0,
  contact_phone VARCHAR(25) NOT NULL DEFAULT '+15550142880',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_cars_status_created (status, created_at),
  KEY idx_cars_brand_model (brand, model)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS car_images (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  car_id INT UNSIGNED NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_car_images_car_order (car_id, sort_order),
  CONSTRAINT fk_car_images_car FOREIGN KEY (car_id) REFERENCES cars (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sell_requests (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  customer_name VARCHAR(120) NOT NULL,
  phone VARCHAR(25) NOT NULL,
  email VARCHAR(190) NOT NULL,
  brand VARCHAR(80) NOT NULL,
  model VARCHAR(100) NOT NULL,
  year SMALLINT UNSIGNED NOT NULL,
  mileage INT UNSIGNED NOT NULL,
  expected_price DECIMAL(12,2) NOT NULL,
  description TEXT NOT NULL,
  image_path VARCHAR(255) DEFAULT NULL,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_sell_requests_status_created (status, created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS messages (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(25) DEFAULT NULL,
  subject VARCHAR(180) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_messages_created (created_at)
) ENGINE=InnoDB;

INSERT INTO cars (brand, model, year, mileage, price, description, body_type, transmission, fuel_type, color, status, featured, contact_phone)
SELECT 'Volvo', 'XC60 B5 AWD', 2022, 24100, 38900, 'A calm, capable SUV with a beautifully considered cabin and the confidence of all-wheel drive. Carefully inspected and ready for its next chapter.', 'SUV', 'Automatic', 'Gasoline', 'Crystal White', 'available', 1, '+15550142880'
WHERE NOT EXISTS (SELECT 1 FROM cars WHERE brand = 'Volvo' AND model = 'XC60 B5 AWD');
INSERT INTO cars (brand, model, year, mileage, price, description, body_type, transmission, fuel_type, color, status, featured, contact_phone)
SELECT 'BMW', '330i Sport', 2021, 28750, 32900, 'A rewarding drive with just the right amount of everyday ease. This 330i has been inspected by our team and presents beautifully inside and out.', 'Sedan', 'Automatic', 'Gasoline', 'Mineral Grey', 'available', 1, '+15550142880'
WHERE NOT EXISTS (SELECT 1 FROM cars WHERE brand = 'BMW' AND model = '330i Sport');
INSERT INTO cars (brand, model, year, mileage, price, description, body_type, transmission, fuel_type, color, status, featured, contact_phone)
SELECT 'Audi', 'A5 Sportback Premium', 2022, 19800, 41900, 'Elegant proportions, a versatile Sportback silhouette, and a quiet, premium interior. A lovely all-rounder for wherever the week takes you.', 'Sportback', 'Automatic', 'Gasoline', 'Glacier White', 'available', 1, '+15550142880'
WHERE NOT EXISTS (SELECT 1 FROM cars WHERE brand = 'Audi' AND model = 'A5 Sportback Premium');
INSERT INTO cars (brand, model, year, mileage, price, description, body_type, transmission, fuel_type, color, status, featured, contact_phone)
SELECT 'Mercedes-Benz', 'C 300 Sedan', 2021, 31500, 35900, 'A composed, comfortable sedan with refined road manners and thoughtful craftsmanship. Inspected, well cared for, and ready to be enjoyed.', 'Sedan', 'Automatic', 'Gasoline', 'Polar White', 'available', 0, '+15550142880'
WHERE NOT EXISTS (SELECT 1 FROM cars WHERE brand = 'Mercedes-Benz' AND model = 'C 300 Sedan');

INSERT INTO car_images (car_id, image_path, sort_order)
SELECT c.id, 'assets/images/car-1.jpg', 0
FROM cars c
WHERE c.brand = 'Volvo' AND c.model = 'XC60 B5 AWD'
AND NOT EXISTS (SELECT 1 FROM car_images ci WHERE ci.car_id = c.id);
INSERT INTO car_images (car_id, image_path, sort_order)
SELECT c.id, 'assets/images/car-2.jpg', 0
FROM cars c
WHERE c.brand = 'BMW' AND c.model = '330i Sport'
AND NOT EXISTS (SELECT 1 FROM car_images ci WHERE ci.car_id = c.id);
INSERT INTO car_images (car_id, image_path, sort_order)
SELECT c.id, 'assets/images/car-3.jpg', 0
FROM cars c
WHERE c.brand = 'Audi' AND c.model = 'A5 Sportback Premium'
AND NOT EXISTS (SELECT 1 FROM car_images ci WHERE ci.car_id = c.id);
INSERT INTO car_images (car_id, image_path, sort_order)
SELECT c.id, 'assets/images/car-4.jpg', 0
FROM cars c
WHERE c.brand = 'Mercedes-Benz' AND c.model = 'C 300 Sedan'
AND NOT EXISTS (SELECT 1 FROM car_images ci WHERE ci.car_id = c.id);
INSERT INTO car_images (car_id, image_path, sort_order)
SELECT c.id, 'assets/images/car-1-detail.jpg', 1
FROM cars c
WHERE c.brand = 'Volvo' AND c.model = 'XC60 B5 AWD'
AND NOT EXISTS (SELECT 1 FROM car_images ci WHERE ci.car_id = c.id AND ci.sort_order = 1);
INSERT INTO car_images (car_id, image_path, sort_order)
SELECT c.id, 'assets/images/car-2-detail.jpg', 1
FROM cars c
WHERE c.brand = 'BMW' AND c.model = '330i Sport'
AND NOT EXISTS (SELECT 1 FROM car_images ci WHERE ci.car_id = c.id AND ci.sort_order = 1);
INSERT INTO car_images (car_id, image_path, sort_order)
SELECT c.id, 'assets/images/car-3-detail.jpg', 1
FROM cars c
WHERE c.brand = 'Audi' AND c.model = 'A5 Sportback Premium'
AND NOT EXISTS (SELECT 1 FROM car_images ci WHERE ci.car_id = c.id AND ci.sort_order = 1);
INSERT INTO car_images (car_id, image_path, sort_order)
SELECT c.id, 'assets/images/car-4-detail.jpg', 1
FROM cars c
WHERE c.brand = 'Mercedes-Benz' AND c.model = 'C 300 Sedan'
AND NOT EXISTS (SELECT 1 FROM car_images ci WHERE ci.car_id = c.id AND ci.sort_order = 1);
