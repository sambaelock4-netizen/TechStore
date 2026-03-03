-- =====================================================
-- TECHSTORE - Base de données pour vente d'équipements informatiques
-- =====================================================

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS techstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techstore;

-- =====================================================
-- TABLE DES UTILISATEURS (clients et admin)
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    postal_code VARCHAR(20),
    role ENUM('client', 'admin') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- TABLE DES ADRESSES
-- =====================================================
CREATE TABLE IF NOT EXISTS addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    city VARCHAR(100) NOT NULL,
    country VARCHAR(100) DEFAULT 'France',
    phone VARCHAR(20),
    is_default TINYINT(1) DEFAULT 0,
    type ENUM('delivery', 'billing', 'both') DEFAULT 'both',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =====================================================
-- TABLE DES CATÉGORIES
-- =====================================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    parent_id INT DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- =====================================================
-- TABLE DES PRODUITS
-- =====================================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT,
    short_description VARCHAR(255),
    price DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    category_id INT,
    image VARCHAR(255),
    images JSON,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- =====================================================
-- TABLE DES COMMANDES
-- =====================================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('en_attente', 'confirme', 'en_preparation', 'expedie', 'livre', 'annule') DEFAULT 'en_attente',
    shipping_address TEXT,
    shipping_city VARCHAR(100),
    shipping_postal_code VARCHAR(20),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =====================================================
-- TABLE DES DÉTAILS DE COMMANDE
-- =====================================================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- =====================================================
-- TABLE DU PANIER (pour utilisateurs connectés)
-- =====================================================
CREATE TABLE IF NOT EXISTS carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_product (user_id, product_id)
);

-- =====================================================
-- DONNÉES DE TEST - CATÉGORIES
-- =====================================================
INSERT INTO categories (name, slug, description, is_active) VALUES
('Ordinateurs', 'ordinateurs', 'Ordinateurs portables et de bureau', 1),
('Composants', 'composants', 'Composants informatiques (CPU, GPU, RAM, SSD)', 1),
('Périphériques', 'peripheriques', 'Souris, claviers, casques, webcams', 1),
('Réseaux', 'reseaux', 'Routeurs, switches, cables réseau', 1),
('Accessoires', 'accessoires', 'Accessoires et divers', 1);

-- =====================================================
-- DONNÉES DE TEST - PRODUITS
-- =====================================================
INSERT INTO products (name, slug, description, short_description, price, stock, category_id, is_featured, is_active) VALUES
('MacBook Pro 14" M3', 'macbook-pro-14-m3', 'MacBook Pro 14 pouces avec Puce M3 Pro, 18GB RAM, 512GB SSD. Écran Liquid Retina XDR, autonomie jusqu à 18h.', 'MacBook Pro 14" M3 Pro - 18GB/512GB', 1999.00, 10, 1, 1, 1),
('Dell XPS 15', 'dell-xps-15', 'Dell XPS 15 9530 - Intel Core i7-13700H, 16GB RAM, 512GB SSD, NVIDIA RTX 4050. Écran 15.6" 3.5K OLED.', 'Dell XPS 15 - i7/16GB/RTX 4050', 1499.00, 15, 1, 1, 1),
('ASUS ROG Strix G16', 'asus-rog-strix-g16', 'ASUS ROG Strix G16 - Intel Core i9-13980HX, 32GB RAM, 1TB SSD, NVIDIA RTX 4070. Écran 16" FHD 165Hz.', 'ASUS ROG Strix - i9/32GB/RTX 4070', 1999.00, 8, 1, 1, 1),
('HP Pavilion Plus 14', 'hp-pavilion-plus-14', 'HP Pavilion Plus 14 - Intel Core i5-1340P, 16GB RAM, 512GB SSD, OLED 2.8K', 'HP Pavilion Plus 14 - i5/16GB/OLED', 899.00, 20, 1, 0, 1),
('Lenovo ThinkPad X1 Carbon', 'lenovo-thinkpad-x1-carbon', 'Lenovo ThinkPad X1 Carbon Gen 11 - Intel Core i7-1365U, 16GB RAM, 512GB SSD. Ultraportable professionnel.', 'ThinkPad X1 Carbon - i7/16GB', 1299.00, 12, 1, 0, 1),
('NVIDIA GeForce RTX 4090', 'nvidia-rtx-4090', 'NVIDIA GeForce RTX 4090 - 24GB GDDR6X. La carte graphique la plus puissante.', 'RTX 4090 - 24GB GDDR6X', 1599.00, 5, 2, 1, 1),
('AMD Ryzen 9 7950X', 'amd-ryzen-9-7950x', 'AMD Ryzen 9 7950X - 16 cœurs, 32 threads, boost jusqu à 5.7GHz', 'Ryzen 9 7950X - 16C/32T', 549.00, 25, 2, 0, 1),
('Corsair Vengeance 32GB', 'corsair-vengeance-32gb', 'Corsair Vengeance DDR5 32GB (2x16GB) 5600MHz - Performance extrême', 'Corsair DDR5 32GB 5600MHz', 129.00, 50, 2, 0, 1),
('Samsung 990 PRO 2TB', 'samsung-990-pro-2tb', 'Samsung 990 PRO NVMe M.2 - 2TB, lecture 7450MB/s, écriture 6900MB/s', 'Samsung 990 PRO 2TB NVMe', 179.00, 40, 2, 1, 1),
('Souris Logitech MX Master 3S', 'logitech-mx-master-3s', 'Logitech MX Master 3S - Souris sans fil premium, Capteur 8000DPI, scroll electromagnetic', 'MX Master 3S - 8000DPI', 99.00, 60, 3, 1, 1),
('Clavier Corsair K70 RGB', 'corsair-k70-rgb', 'Corsair K70 RGB PRO - Clavier mécanique Cherry MX, RGB, reposer-poignets', 'K70 RGB PRO - Cherry MX', 149.00, 35, 3, 0, 1),
('Casque Sony WH-1000XM5', 'sony-wh-1000xm5', 'Sony WH-1000XM5 - Casque à réduction de bruit active, autonomie 30h', 'WH-1000XM5 - ANC', 349.00, 30, 3, 1, 1),
('Webcam Logitech Brio 4K', 'logitech-brio-4k', 'Logitech Brio 4K - Webcam Ultra HD, HDR, correction automatique de luminosité', 'Brio 4K - Ultra HD', 199.00, 25, 3, 0, 1),
('Routeur ASUS RT-AX88U', 'asus-rt-ax88u', 'ASUS RT-AX88U Pro - WiFi 6E, AX6000, 8 ports Gigabit, protection ASUS AiProtection', 'RT-AX88U Pro - WiFi 6E', 349.00, 15, 4, 0, 1),
('TP-Link Archer AX73', 'tp-link-archer-ax73', 'TP-Link Archer AX73 - WiFi 6, AX5400, 6 antennes, OneMesh', 'Archer AX73 - WiFi 6', 129.00, 30, 4, 0, 1),
('Cable RJ45 Cat8 5m', 'cable-rj45-cat8-5m', 'Cable réseau RJ45 Cat8 blindé - 5m, 40Gbps, compatible tout appareil', 'Cat8 - 5m - Noir', 19.00, 100, 4, 0, 1),
('Hub USB-C Anker 7-en-1', 'anker-hub-usb-c-7', 'Anker 7-en-1 - Hub USB-C avec HDMI 4K, 3x USB-A, SD, microSD, 100W PD', 'Anker 7-en-1 USB-C', 49.00, 80, 5, 0, 1),
('Support laptop aluminium', 'support-laptop-aluminium', 'Support laptop aluminium ergonomique - Réglable en hauteur, pliable', 'Support aluminium - Gris', 35.00, 50, 5, 0, 1);

-- =====================================================
-- DONNÉES DE TEST - ADMIN
-- =====================================================
INSERT INTO users (firstname, lastname, email, password, role) VALUES
('Admin', 'TechStore', 'admin@techstore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- =====================================================
-- DONNÉES DE TEST - CLIENT
-- =====================================================
INSERT INTO users (firstname, lastname, email, password, phone, address, city, postal_code, role) VALUES
('Jean', 'Dupont', 'jean.dupont@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0612345678', '123 Rue de la Paix', 'Paris', '75001', 'client');
