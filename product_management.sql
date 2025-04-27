-- Create database
CREATE DATABASE product_management;
USE product_management;

-- Create category table
CREATE TABLE category (
    C_id INT AUTO_INCREMENT PRIMARY KEY,
    C_pid INT,
    C_name VARCHAR(50) NOT NULL
);

-- Create product table
CREATE TABLE product (
    P_id INT AUTO_INCREMENT PRIMARY KEY,
    C_id INT,
    P_name VARCHAR(50) NOT NULL,
    FOREIGN KEY (C_id) REFERENCES category(C_id)
);
ALTER TABLE product ADD COLUMN price DECIMAL(10,2) NOT NULL DEFAULT 1000.00;

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    password VARCHAR(50)
);

CREATE TABLE customer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    address VARCHAR(255)
);

CREATE TABLE shopping_cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(50),
    product_id INT,
    quantity INT,
    FOREIGN KEY (product_id) REFERENCES product(P_id)
);
ALTER TABLE shopping_cart ADD COLUMN price DECIMAL(10,2) NOT NULL DEFAULT 1000.00;


CREATE TABLE wish_list (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(50),
    product_id INT,
    FOREIGN KEY (product_id) REFERENCES product(P_id)
);
ALTER TABLE wish_list 
ADD COLUMN price DECIMAL(10,2) NOT NULL DEFAULT 1000.00;
ALTER TABLE wish_list 
ADD COLUMN quantity INT NOT NULL DEFAULT 1;






