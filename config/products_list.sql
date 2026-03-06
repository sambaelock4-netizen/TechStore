-- =====================================================
-- LISTE DES PRODUITS POUR TECHSTORE
-- Avec promotion et production
-- =====================================================

USE techstore;

-- =====================================================
-- PRODUITS INFORMATIQUES
-- =====================================================

-- Ordinateurs Portables
INSERT INTO products (name, slug, description, short_description, price, old_price, discount, stock, category_id, is_featured, is_active, is_production, is_promotion, promotion_price, promotion_start_date, promotion_end_date, image) VALUES
('MacBook Pro 14" M3', 'macbook-pro-14-m3', 'MacBook Pro 14 pouces avec Puce M3 Pro, 18GB RAM, 512GB SSD. Écran Liquid Retina XDR, autonomie jusqu à 18h.', 'MacBook Pro 14" M3 Pro - 18GB/512GB', 1999000, 2200000, 9, 15, 1, 1, 1, 1, 1, 1999000, '2025-01-01', '2025-12-31', 'product_1.jpg'),
('Dell XPS 15', 'dell-xps-15', 'Dell XPS 15 9530 - Intel Core i7-13700H, 16GB RAM, 512GB SSD, NVIDIA RTX 4050. Écran 15.6" 3.5K OLED.', 'Dell XPS 15 - i7/16GB/RTX 4050', 1499000, NULL, 0, 20, 1, 1, 1, 1, 0, NULL, NULL, NULL, 'product_2.jpg'),
('ASUS ROG Strix G16', 'asus-rog-strix-g16', 'ASUS ROG Strix G16 - Intel Core i9-13980HX, 32GB RAM, 1TB SSD, NVIDIA RTX 4070. Écran 16" FHD 165Hz.', 'ASUS ROG Strix - i9/32GB/RTX 4070', 1999000, 2300000, 13, 10, 1, 1, 1, 1, 1, 1999000, '2025-01-01', '2025-06-30', 'product_3.jpg'),
('HP Pavilion Plus 14', 'hp-pavilion-plus-14', 'HP Pavilion Plus 14 - Intel Core i5-1340P, 16GB RAM, 512GB SSD, OLED 2.8K', 'HP Pavilion Plus 14 - i5/16GB/OLED', 899000, 999000, 10, 25, 1, 0, 1, 1, 1, 899000, '2025-01-01', '2025-03-31', 'product_4.jpg'),
('Lenovo ThinkPad X1 Carbon', 'lenovo-thinkpad-x1-carbon', 'Lenovo ThinkPad X1 Carbon Gen 11 - Intel Core i7-1365U, 16GB RAM, 512GB SSD. Ultraportable professionnel.', 'ThinkPad X1 Carbon - i7/16GB', 1299000, NULL, 0, 18, 1, 0, 1, 1, 0, NULL, NULL, NULL, 'product_5.jpg'),
('Acer Aspire 5', 'acer-aspire-5', 'Acer Aspire 5 - Intel Core i5-1235U, 8GB RAM, 512GB SSD, Écran 15.6" FHD', 'Acer Aspire 5 - i5/8GB', 549000, 650000, 15, 30, 1, 0, 1, 1, 1, 549000, '2025-01-01', '2025-12-31', 'product_6.jpg'),
('MSI Katana 15', 'msi-katana-15', 'MSI Katana 15 - Intel Core i7-13650HX, 16GB RAM, 1TB SSD, RTX 4060, Écran 15.6" FHD 144Hz', 'MSI Katana - i7/RTX 4060', 1499000, 1750000, 14, 12, 1, 1, 1, 1, 1, 1499000, '2025-01-01', '2025-06-30', 'product_7.jpg');

-- Composants
INSERT INTO products (name, slug, description, short_description, price, old_price, discount, stock, category_id, is_featured, is_active, is_production, is_promotion, promotion_price, promotion_start_date, promotion_end_date, image) VALUES
('NVIDIA GeForce RTX 4090', 'nvidia-rtx-4090', 'NVIDIA GeForce RTX 4090 - 24GB GDDR6X. La carte graphique la plus puissante.', 'RTX 4090 - 24GB GDDR6X', 1599000, 1800000, 11, 8, 2, 1, 1, 1, 1, 1599000, '2025-01-01', '2025-12-31', 'product_8.jpg'),
('AMD Ryzen 9 7950X', 'amd-ryzen-9-7950x', 'AMD Ryzen 9 7950X - 16 cœurs, 32 threads, boost jusqu à 5.7GHz', 'Ryzen 9 7950X - 16C/32T', 549000, NULL, 0, 30, 2, 0, 1, 1, 0, NULL, NULL, NULL, 'product_9.jpg'),
('Corsair Vengeance 32GB', 'corsair-vengeance-32gb', 'Corsair Vengeance DDR5 32GB (2x16GB) 5600MHz - Performance extrême', 'Corsair DDR5 32GB 5600MHz', 129000, 150000, 14, 50, 2, 0, 1, 1, 1, 129000, '2025-01-01', '2025-03-31', 'product_10.jpg'),
('Samsung 990 PRO 2TB', 'samsung-990-pro-2tb', 'Samsung 990 PRO NVMe M.2 - 2TB, lecture 7450MB/s, écriture 6900MB/s', 'Samsung 990 PRO 2TB NVMe', 179000, 220000, 18, 45, 2, 1, 1, 1, 1, 179000, '2025-01-01', '2025-12-31', 'product_11.jpg'),
('Intel Core i9-14900K', 'intel-core-i9-14900k', 'Intel Core i9-14900K - 24 cœurs, 32 threads, boost jusqu à 6.0GHz', 'i9-14900K - 24C/32T', 599000, NULL, 0, 20, 2, 1, 1, 1, 0, NULL, NULL, NULL, 'product_12.jpg'),
('ASUS ROG STRIX RTX 4080', 'asus-rog-strix-rtx-4080', 'ASUS ROG STRIX RTX 4080 - 16GB GDDR6X, RGB, overclocking', 'ROG STRIX RTX 4080 - 16GB', 1299000, 1450000, 10, 10, 2, 1, 1, 1, 1, 1299000, '2025-01-01', '2025-06-30', 'product_13.jpg'),
('Kingston Fury Beast 64GB', 'kingston-fury-beast-64gb', 'Kingston Fury Beast DDR5 64GB (2x32GB) 5600MHz', 'Kingston Fury 64GB DDR5', 249000, NULL, 0, 35, 2, 0, 1, 1, 0, NULL, NULL, NULL, 'product_14.jpg');

-- Périphériques
INSERT INTO products (name, slug, description, short_description, price, old_price, discount, stock, category_id, is_featured, is_active, is_production, is_promotion, promotion_price, promotion_start_date, promotion_end_date, image) VALUES
('Souris Logitech MX Master 3S', 'logitech-mx-master-3s', 'Logitech MX Master 3S - Souris sans fil premium, Capteur 8000DPI, scroll electromagnetic', 'MX Master 3S - 8000DPI', 99000, 120000, 17, 60, 3, 1, 1, 1, 1, 99000, '2025-01-01', '2025-12-31', 'product_15.jpg'),
('Clavier Corsair K70 RGB', 'corsair-k70-rgb', 'Corsair K70 RGB PRO - Clavier mécanique Cherry MX, RGB, reposer-poignets', 'K70 RGB PRO - Cherry MX', 149000, NULL, 0, 40, 3, 0, 1, 1, 0, NULL, NULL, NULL, 'product_16.jpg'),
('Casque Sony WH-1000XM5', 'sony-wh-1000xm5', 'Sony WH-1000XM5 - Casque à réduction de bruit active, autonomie 30h', 'WH-1000XM5 - ANC', 349000, 399000, 12, 35, 3, 1, 1, 1, 1, 349000, '2025-01-01', '2025-06-30', 'product_17.jpg'),
('Webcam Logitech Brio 4K', 'logitech-brio-4k', 'Logitech Brio 4K - Webcam Ultra HD, HDR, correction automatique de luminosité', 'Brio 4K - Ultra HD', 199000, 250000, 20, 25, 3, 0, 1, 1, 1, 199000, '2025-01-01', '2025-03-31', 'product_18.jpg'),
('Souris Razer DeathAdder V3', 'razer-deathadder-v3', 'Razer DeathAdder V3 - Souris gaming, 30K DPI, switches optiques', 'DeathAdder V3 Pro', 89000, NULL, 0, 45, 3, 1, 1, 1, 0, NULL, NULL, NULL, 'product_19.jpg'),
('Clavier Logitech MX Keys', 'logitech-mx-keys', 'Logitech MX Keys - Clavier sans fil, rétroéclairage, connexion multi-appareils', 'MX Keys - Sans fil', 129000, 150000, 14, 30, 3, 0, 1, 1, 1, 129000, '2025-01-01', '2025-12-31', 'product_20.jpg'),
('Microphone Blue Yeti', 'blue-yeti-microphone', 'Blue Yeti - Microphone USB studio, 4 patterns, qualité broadcast', 'Blue Yeti - Studio', 149000, NULL, 0, 20, 3, 0, 1, 1, 0, NULL, NULL, NULL, 'product_21.jpg'),
('Écran Samsung Odyssey G7', 'samsung-odyssey-g7', 'Samsung Odyssey G7 - Écran 27" QHD 240Hz, courbé, HDR600', 'Odyssey G7 - 27" QHD', 699000, 850000, 17, 15, 3, 1, 1, 1, 1, 699000, '2025-01-01', '2025-06-30', 'product_22.jpg');

-- Réseau
INSERT INTO products (name, slug, description, short_description, price, old_price, discount, stock, category_id, is_featured, is_active, is_production, is_promotion, promotion_price, promotion_start_date, promotion_end_date, image) VALUES
('Routeur ASUS RT-AX88U', 'asus-rt-ax88u', 'ASUS RT-AX88U Pro - WiFi 6E, AX6000, 8 ports Gigabit, protection ASUS AiProtection', 'RT-AX88U Pro - WiFi 6E', 349000, 420000, 16, 20, 4, 0, 1, 1, 1, 349000, '2025-01-01', '2025-12-31', 'product_23.jpg'),
('TP-Link Archer AX73', 'tp-link-archer-ax73', 'TP-Link Archer AX73 - WiFi 6, AX5400, 6 antennes, OneMesh', 'Archer AX73 - WiFi 6', 129000, NULL, 0, 40, 4, 0, 1, 1, 0, NULL, NULL, NULL, 'product_24.jpg'),
('Cable RJ45 Cat8 5m', 'cable-rj45-cat8-5m', 'Cable réseau RJ45 Cat8 blindé - 5m, 40Gbps, compatible tout appareil', 'Cat8 - 5m - Noir', 19000, 2500, 24, 100, 4, 0, 1, 1, 1, 19000, '2025-01-01', '2025-12-31', 'product_25.jpg'),
('Switch Netgear GS108', 'netgear-gs108', 'Netgear GS108 - Switch 8 ports Gigabit, Plug-and-Play', 'GS108 - 8 ports Gigabit', 49000, NULL, 0, 50, 4, 0, 1, 1, 0, NULL, NULL, NULL, 'product_26.jpg'),
('Adaptateur WiFi TP-Link', 'tp-link-adapter-wifi', 'TP-Link Archer TX20U - Adaptateur WiFi 6 USB, AX1800', 'Archer TX20U - WiFi 6', 39000, 5000, 22, 60, 4, 0, 1, 1, 1, 39000, '2025-01-01', '2025-03-31', 'product_27.jpg');

-- Accessoires
INSERT INTO products (name, slug, description, short_description, price, old_price, discount, stock, category_id, is_featured, is_active, is_production, is_promotion, promotion_price, promotion_start_date, promotion_end_date, image) VALUES
('Hub USB-C Anker 7-en-1', 'anker-hub-usb-c-7', 'Anker 7-en-1 - Hub USB-C avec HDMI 4K, 3x USB-A, SD, microSD, 100W PD', 'Anker 7-en-1 USB-C', 49000, 6500, 24, 80, 5, 0, 1, 1, 1, 49000, '2025-01-01', '2025-12-31', 'product_28.jpg'),
('Support laptop aluminium', 'support-laptop-aluminium', 'Support laptop aluminium ergonomique - Réglable en hauteur, pliable', 'Support aluminium - Gris', 35000, 4500, 22, 50, 5, 0, 1, 1, 1, 35000, '2025-01-01', '2025-06-30', 'product_29.jpg'),
('Sacoche pour laptop 15.6"', 'sacoche-laptop-15-6', 'Sacoche de transport pour laptop 15.6" - Protection antichoc, bandoulière', 'Sacoche 15.6" - Noir', 25000, NULL, 0, 70, 5, 0, 1, 1, 0, NULL, NULL, NULL, 'product_30.jpg'),
('Webcam Logitech C920', 'logitech-c920', 'Logitech C920 - Webcam HD 1080p, autofocus, correction lumière', 'C920 - HD 1080p', 79000, 9900, 20, 40, 5, 0, 1, 1, 1, 79000, '2025-01-01', '2025-03-31', 'product_31.jpg'),
('Disque SSD externe Samsung 1TB', 'samsung-ssd-externe-1tb', 'Samsung T7 Shield - SSD externe 1TB, USB 3.2, résistant chocs', 'T7 Shield - 1TB', 149000, 180000, 17, 30, 5, 1, 1, 1, 1, 149000, '2025-01-01', '2025-12-31', 'product_32.jpg'),
('Enceinte Bluetooth JBL Flip 6', 'jbl-flip-6', 'JBL Flip 6 - Enceinte portable,防水, 12h autonomie, son puissant', 'JBL Flip 6 -防水', 129000, 150000, 14, 35, 5, 0, 1, 1, 1, 129000, '2025-01-01', '2025-06-30', 'product_33.jpg'),
('Power Bank Anker 20000mAh', 'anker-powerbank-20000', 'Anker PowerCore 20000mAh - Charge rapide, 2 ports USB, airline safe', 'PowerCore 20000mAh', 49000, NULL, 0, 45, 5, 0, 1, 1, 0, NULL, NULL, NULL, 'product_34.jpg'),
('Cable HDMI 2.1 3m', 'cable-hdmi-2-1-3m', 'Cable HDMI 2.1 haute vitesse - 3m, 48Gbps, 8K, eARC', 'HDMI 2.1 - 3m', 25000, 3500, 28, 90, 5, 0, 1, 1, 1, 25000, '2025-01-01', '2025-12-31', 'product_35.jpg');

-- Mise à jour des produits existants sans old_price
UPDATE products SET old_price = price * 1.2 WHERE old_price IS NULL AND is_promotion = 1;
