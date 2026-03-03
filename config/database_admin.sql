-- =====================================================
-- MISE À JOUR - Rôles et Permissions administrateur
-- =====================================================

-- Modifier la table users pour ajouter plus de rôles
ALTER TABLE users MODIFY COLUMN role ENUM('client', 'admin', 'super_admin', 'product_manager', 'order_manager') DEFAULT 'client';

-- Ajouter une colonne pour les permissions
ALTER TABLE users ADD COLUMN permissions JSON NULL AFTER role;

-- Mettre à jour l'admin existant avec le rôle super_admin
UPDATE users SET role = 'super_admin' WHERE email = 'admin@techstore.com';

-- Créer un utilisateur administrateur supplémentaire pour test
INSERT INTO users (firstname, lastname, email, password, role, permissions) VALUES
('Gestionnaire', 'Produits', 'product@techstore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'product_manager', '{"products": ["read", "write", "delete"], "categories": ["read", "write"]}'),
('Gestionnaire', 'Commandes', 'orders@techstore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'order_manager', '{"orders": ["read", "write"], "users": ["read"]}');

-- Créer table des sessions admin pour sécurité
CREATE TABLE IF NOT EXISTS admin_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_session_token (session_token),
    INDEX idx_expires_at (expires_at)
);

-- Créer table des logs d'activité admin
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    details JSON,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
);
