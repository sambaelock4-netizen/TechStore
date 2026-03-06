-- Script pour ajouter les champs de promotion à la table products
-- Exécuter ce script si la table products existe déjà

-- Ajouter les nouvelles colonnes si elles n'existent pas
ALTER TABLE products ADD COLUMN IF NOT EXISTS old_price DECIMAL(10, 2) DEFAULT NULL AFTER price;
ALTER TABLE products ADD COLUMN IF NOT EXISTS discount INT DEFAULT 0 AFTER old_price;
ALTER TABLE products ADD COLUMN IF NOT EXISTS is_promotion TINYINT(1) DEFAULT 0 AFTER is_featured;

-- Mettre à jour quelques produits avec des promotions
UPDATE products SET old_price = 2299.00, discount = 15, is_promotion = 1 WHERE slug = 'macbook-pro-14-m3';
UPDATE products SET old_price = 1799.00, discount = 20, is_promotion = 1 WHERE slug = 'dell-xps-15';
UPDATE products SET old_price = 2399.00, discount = 18, is_promotion = 1 WHERE slug = 'asus-rog-strix-g16';
UPDATE products SET old_price = 1899.00, discount = 25, is_promotion = 1 WHERE slug = 'nvidia-rtx-4090';
UPDATE products SET old_price = 129.00, discount = 30, is_promotion = 1 WHERE slug = 'corsair-vengeance-32gb';
UPDATE products SET old_price = 249.00, discount = 40, is_promotion = 1 WHERE slug = 'sony-wh-1000xm5';
