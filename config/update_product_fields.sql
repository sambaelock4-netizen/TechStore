-- =====================================================
-- MISE À JOUR DE LA TABLE PRODUCTS
-- Ajout des champs pour les promotions et la production
-- =====================================================

USE techstore;

-- Ajouter les champs de promotion manquants
ALTER TABLE products 
ADD COLUMN IF NOT EXISTS promotion_price DECIMAL(10, 2) DEFAULT NULL AFTER discount,
ADD COLUMN IF NOT EXISTS promotion_start_date DATE DEFAULT NULL AFTER promotion_price,
ADD COLUMN IF NOT EXISTS promotion_end_date DATE DEFAULT NULL AFTER promotion_start_date,
ADD COLUMN IF NOT EXISTS is_production TINYINT(1) DEFAULT 0 AFTER is_active;

-- Mettre à jour les produits en promotion pour utiliser is_promotion
UPDATE products SET is_promotion = 1 WHERE discount > 0 AND discount IS NOT NULL;

-- Activer is_production pour les produits actifs avec du stock
UPDATE products SET is_production = 1 WHERE is_active = 1 AND stock > 0;
