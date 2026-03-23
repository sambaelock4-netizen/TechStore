-- =============================================
-- TECHSTORE - Catégories et Produits
-- =============================================

-- =============================================
-- CATÉGORIES
-- =============================================

-- Catégorie 1: Ordinateurs
INSERT INTO categories (name, slug, description, is_active) VALUES 
('Ordinateurs', 'ordinateurs', 'PC portables, de bureau et stations de travail pour tous vos besoins informatiques.', 1),
('Composants', 'composants', 'Cartes graphiques, processeurs, cartes mères et RAM pour booster vos performances.', 1),
('Gaming', 'gaming', 'Consoles de jeux, chaises gaming et accessoires spécialisés pour les gamers.', 1),
('Périphériques', 'peripheriques', 'Écrans, claviers, souris et tous vos périphériques informatiques.', 1),
('Stockage', 'stockage', 'Disques SSD, disques durs externes, clés USB et solutions de stockage.', 1),
('Audio', 'audio', 'Casques, écouteurs et enceintes pour une expérience sonore optimale.', 1),
('Réseaux', 'reseaux', 'Routeurs Wi-Fi, répéteurs et switchs pour une connexion stable.', 1),
('Accessoires', 'accessoires', 'Hubs USB-C, câbles HDMI, sacoches et accessoires divers.', 1);

-- =============================================
-- PRODUITS - ORDINATEURS (10 produits)
-- =============================================

INSERT INTO products (name, slug, description, short_description, price, stock, category_id, is_featured, is_active, image) VALUES
('MacBook Pro 14" M3', 'macbook-pro-14-m3', 'Le MacBook Pro 14 pouces avec la puissante puce M3. Performances révolutionnaires pour les professionnels.', 'MacBook Pro 14" M3 - 18GB/512GB', 149999, 15, 1, 1, 1, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800&q=80'),
('Dell XPS 15', 'dell-xps-15', 'Ordinateur portable XPS 15 avec écran OLED 15.6" et processeur Intel Core i7 de 13e génération.', 'Dell XPS 15 - i7/32GB/1TB', 119999, 8, 1, 1, 1, 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?auto=format&fit=crop&w=800&q=80'),
('HP Spectre x360', 'hp-spectre-x360', 'PC portable convertible HP Spectre x360 avec écran tactile 14" et charnière 360°.', 'HP Spectre x360 - i7/16GB/512GB', 99999, 12, 1, 0, 1, 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=800&q=80'),
('Lenovo ThinkPad X1 Carbon', 'lenovo-thinkpad-x1-carbon', 'Ultrabook professionnel Lenovo ThinkPad X1 Carbon avec écran 14" et batterie longue durée.', 'ThinkPad X1 Carbon - i7/16GB/512GB', 109999, 6, 1, 1, 1, 'https://images.unsplash.com/photo-1611186871525-9a0d0c18efcb?auto=format&fit=crop&w=800&q=80'),
('ASUS ROG Strix G16', 'asus-rog-strix-g16', 'PC portable gaming ASUS ROG Strix G16 avec RTX 4070 et écran 165Hz pour une expérience gaming optimale.', 'ASUS ROG Strix G16 - RTX 4070', 139999, 10, 1, 1, 1, 'https://images.unsplash.com/photo-1612832021455-245704c6755a?auto=format&fit=crop&w=800&q=80'),
('Acer Aspire 5', 'acer-aspire-5', 'PC portable Acer Aspire 5 performant et abordable, idéal pour les tâches quotidiennes.', 'Acer Aspire 5 - i5/8GB/512GB', 49999, 25, 1, 0, 1, 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?auto=format&fit=crop&w=800&q=80'),
('MSI Creator Z17', 'msi-creator-z17', 'Station de travail mobile MSI Creator Z17 pour les créateurs de contenu professionnels.', 'MSI Creator Z17 - i9/RTX 4080', 199999, 4, 1, 1, 1, 'https://images.unsplash.com/photo-1588702547923-7093a6c3ba33?auto=format&fit=crop&w=800&q=80'),
('Apple Mac Mini M2', 'apple-mac-mini-m2', 'Mac Mini compact et puissant avec la puce M2, parfait pour votre bureau.', 'Mac Mini M2 - 16GB/512GB', 79999, 20, 1, 0, 1, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800&q=80'),
('Dell Inspiron 24', 'dell-inspiron-24', 'PC tout-en-un Dell Inspiron 24 avec écran Full HD et design élégant.', 'Dell Inspiron 24 - i5/16GB/1TB', 69999, 15, 1, 0, 1, 'https://images.unsplash.com/photo-1547082299-de196ea013d6?auto=format&fit=crop&w=800&q=80'),
('HP Pavilion Plus 14', 'hp-pavilion-plus-14', 'HP Pavilion Plus 14 avec écran OLED 2.8K et processeur Intel de 13e génération.', 'HP Pavilion Plus 14 - i7/16GB/1TB', 89999, 18, 1, 0, 1, 'https://images.unsplash.com/photo-1484788984921-03950022c9ef?auto=format&fit=crop&w=800&q=80');

-- =============================================
-- PRODUITS - COMPOSANTS (10 produits)
-- =============================================

INSERT INTO products (name, slug, description, short_description, price, stock, category_id, is_featured, is_active, image) VALUES
('NVIDIA GeForce RTX 4090', 'nvidia-geforce-rtx-4090', 'La carte graphique la plus puissante de NVIDIA avec 24GB de mémoire GDDR6X.', 'NVIDIA RTX 4090 - 24GB', 159999, 5, 2, 1, 1, 'https://images.unsplash.com/photo-1591488320449-011701bb6704?auto=format&fit=crop&w=800&q=80'),
('AMD Ryzen 9 7950X', 'amd-ryzen-9-7950x', 'Processeur haut de gamme AMD Ryzen 9 avec 16 cœurs et 32 threads.', 'AMD Ryzen 9 7950X - 16 cœurs', 59999, 12, 2, 1, 1, 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?auto=format&fit=crop&w=800&q=80'),
('Intel Core i9-14900K', 'intel-core-i9-14900k', 'Processeur Intel Core i9 de 14e génération avec performances exceptionnelles.', 'Intel i9-14900K - 24 cœurs', 54999, 8, 2, 1, 1, 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?auto=format&fit=crop&w=800&q=80'),
('ASUS ROG Maximus Z790', 'asus-rog-maximus-z790', 'Carte mère ASUS ROG Maximus Z790 pour processeurs Intel de 12e et 13e génération.', 'ASUS ROG Maximus Z790', 49999, 10, 2, 0, 1, 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80'),
('G.Skill Trident Z5 RGB', 'gskill-trident-z5-rgb', 'Kit de mémoire RAM DDR5 32GB (2x16GB) avec éclairage RGB.', 'G.Skill Trident Z5 - 32GB DDR5', 19999, 25, 2, 0, 1, 'https://images.unsplash.com/photo-1562976540-1502c2145851?auto=format&fit=crop&w=800&q=80'),
('Samsung 990 PRO 2TB', 'samsung-990-pro-2tb', 'SSD NVMe Samsung 990 PRO avec vitesses de lecture jusqu à 7450 MB/s.', 'Samsung 990 PRO - 2TB', 24999, 30, 2, 1, 1, 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?auto=format&fit=crop&w=800&q=80'),
('Corsair RM1000x', 'corsair-rm1000x', 'Alimentation Corsair RM1000x 1000W 80+ Gold pour PC gaming et workstation.', 'Corsair RM1000x - 1000W', 14999, 20, 2, 0, 1, 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80'),
('Noctua NH-D15', 'noctua-nh-d15', 'Refroidissement CPU Noctua NH-D15, le plus performant pour votre processeur.', 'Noctua NH-D15', 9999, 15, 2, 0, 1, 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?auto=format&fit=crop&w=800&q=80'),
('MSI MEG Z790 ACE', 'msi-meg-z790-ace', 'Carte mère MSI MEG Z790 ACE avec design premium et fonctionnalités avancées.', 'MSI MEG Z790 ACE', 59999, 6, 2, 1, 1, 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80'),
('Kingston Fury Beast', 'kingston-fury-beast', 'Mémoire Kingston Fury Beast DDR5 64GB pour des performances extrêmes.', 'Kingston Fury Beast - 64GB', 34999, 18, 2, 0, 1, 'https://images.unsplash.com/photo-1562976540-1502c2145851?auto=format&fit=crop&w=800&q=80');

-- =============================================
-- PRODUITS - GAMING (10 produits)
-- =============================================

INSERT INTO products (name, slug, description, short_description, price, stock, category_id, is_featured, is_active, image) VALUES
('PlayStation 5', 'playstation-5', 'Console PS5 avec lecteur de disque, Graphismes 4K et jeux en 120fps.', 'PlayStation 5 Standard', 49999, 20, 3, 1, 1, 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?auto=format&fit=crop&w=800&q=80'),
('Xbox Series X', 'xbox-series-x', 'Console Xbox Series X la plus puissante avec 12 teraflops de puissance.', 'Xbox Series X - 1TB', 49999, 18, 3, 1, 1, 'https://images.unsplash.com/photo-1621259182978-fbf93132d53d?auto=format&fit=crop&w=800&q=80'),
('Nintendo Switch OLED', 'nintendo-switch-oled', 'Console Nintendo Switch avec écran OLED pour des couleurs vibrantes.', 'Nintendo Switch OLED', 34999, 25, 3, 1, 1, 'https://images.unsplash.com/photo-1578303512597-81e6cc155b3e?auto=format&fit=crop&w=800&q=80'),
('Razer Iskur V2', 'razer-iskur-v2', 'Chaise gaming Razer Iskur V2 avec soutien lombaire adjustable.', 'Razer Iskur V2 - Noir', 44999, 10, 3, 1, 1, 'https://images.unsplash.com/photo-1593640408182-31c228745a74?auto=format&fit=crop&w=800&q=80'),
('Secretlab Titan Evo', 'secretlab-titan-evo', 'Chaise gaming Secretlab Titan Evo avec mousse à mémoire de forme.', 'Secretlab Titan Evo 2024', 39999, 12, 3, 1, 1, 'https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=800&q=80'),
('Logitech G Pro X', 'logitech-g-pro-x', 'Casque gaming Logitech G Pro X avec son surround 7.1.', 'Logitech G Pro X - Noir', 12999, 30, 3, 0, 1, 'https://images.unsplash.com/photo-1612198188060-c7c2a3b66eae?auto=format&fit=crop&w=800&q=80'),
('Razer DeathAdder V3', 'razer-deathadder-v3', 'Souris gaming Razer DeathAdder V3 avec capteur 30K et poids ultra-léger.', 'Razer DeathAdder V3 Pro', 8999, 35, 3, 0, 1, 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=800&q=80'),
('SteelSeries Apex Pro', 'steelseries-apex-pro', 'Clavier gaming SteelSeries Apex Pro avec switches OmniPoint adjustables.', 'SteelSeries Apex Pro', 17999, 15, 3, 0, 1, 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?auto=format&fit=crop&w=800&q=80'),
('Elgato Stream Deck', 'elgato-stream-deck', 'Périphérique de streaming Elgato Stream Deck pour créer votre contenu.', 'Elgato Stream Deck MK.2', 11999, 20, 3, 0, 1, 'https://images.unsplash.com/photo-1593640408182-31c228745a74?auto=format&fit=crop&w=800&q=80'),
('PlayStation VR2', 'playstation-vr2', 'Casque de réalité virtuelle PS VR2 pour une immersion totale.', 'PlayStation VR2', 54999, 8, 3, 1, 1, 'https://images.unsplash.com/photo-1617802690992-15d93263d3a9?auto=format&fit=crop&w=800&q=80');

-- =============================================
-- PRODUITS - PÉRIPHÉRIQUES (10 produits)
-- =============================================

INSERT INTO products (name, slug, description, short_description, price, stock, category_id, is_featured, is_active, image) VALUES
('Samsung Odyssey G9', 'samsung-odyssey-g9', 'Moniteur gaming Samsung Odyssey G9 incurvé 49" avec DQHD et 240Hz.', 'Samsung Odyssey G9 - 49"', 79999, 6, 4, 1, 1, 'https://images.unsplash.com/photo-1593642634315-48f5414c3ad9?auto=format&fit=crop&w=800&q=80'),
('LG UltraGear 27GP950', 'lg-ultragear-27gp950', 'Moniteur LG UltraGear 27" 4K avec HDMI 2.1 et 144Hz.', 'LG 27GP950-B - 4K 144Hz', 59999, 10, 4, 1, 1, 'https://images.unsplash.com/photo-1527443224154-c4a573d5e6d0?auto=format&fit=crop&w=800&q=80'),
('Dell UltraSharp U2723QE', 'dell-ultrasharp-u2723qe', 'Moniteur Dell 27" 4K avec dalle IPS Black et USB-C.', 'Dell U2723QE - 4K USB-C', 44999, 15, 4, 0, 1, 'https://images.unsplash.com/photo-1560472355-536de3962603?auto=format&fit=crop&w=800&q=80'),
('Apple Studio Display', 'apple-studio-display', 'Écran Apple Studio Display 27" 5K avec caméra et micros intégrés.', 'Apple Studio Display - Verre standard', 79999, 8, 4, 1, 1, 'https://images.unsplash.com/photo-1527443224154-c4a573d5e6d0?auto=format&fit=crop&w=800&q=80'),
('Keychron Q1 Pro', 'keychron-q1-pro', 'Clavier mécanique Keychron Q1 Pro avec switches customisables.', 'Keychron Q1 Pro - Knob', 17999, 20, 4, 0, 1, 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?auto=format&fit=crop&w=800&q=80'),
('Logitech MX Master 3S', 'logitech-mx-master-3s', 'Souris Logitech MX Master 3S avec défilement silencieux et CapMouse.', 'Logitech MX Master 3S', 9999, 40, 4, 1, 1, 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?auto=format&fit=crop&w=800&q=80'),
('Apple Magic Keyboard', 'apple-magic-keyboard', 'Clavier Apple Magic Keyboard avec Touch ID pour Mac.', 'Apple Magic Keyboard - Touch ID', 9999, 30, 4, 0, 1, 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?auto=format&fit=crop&w=800&q=80'),
('Wacom Cintiq 16', 'wacom-cintiq-16', 'Tablette graphique Wacom Cintiq 16 avec écran Full HD et stylet.', 'Wacom Cintiq 16', 44999, 8, 4, 0, 1, 'https://images.unsplash.com/photo-1615473967657-9dc21773bf37?auto=format&fit=crop&w=800&q=80'),
('BenQ ScreenBar Plus', 'benq-screenbar-plus', 'Lumière de bureau BenQ ScreenBar Plus pour vos sessions de travail.', 'BenQ ScreenBar Plus', 8999, 25, 4, 0, 1, 'https://images.unsplash.com/photo-1593640495253-23196b27a87f?auto=format&fit=crop&w=800&q=80'),
('HyperX Cloud III', 'hyperx-cloud-iii', 'Casque HyperX Cloud III avec son surround et microphone antibruit.', 'HyperX Cloud III', 8999, 35, 4, 0, 1, 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=800&q=80');

-- =============================================
-- PRODUITS - STOCKAGE (10 produits)
-- =============================================

INSERT INTO products (name, slug, description, short_description, price, stock, category_id, is_featured, is_active, image) VALUES
('Samsung 990 PRO 4TB', 'samsung-990-pro-4tb', 'SSD NVMe Samsung 990 PRO 4TB avec vitesses ultra-rapides.', 'Samsung 990 PRO - 4TB', 44999, 20, 5, 1, 1, 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?auto=format&fit=crop&w=800&q=80'),
('WD Black SN850X', 'wd-black-sn850x', 'SSD WD Black SN850X 2TB optimisé pour le gaming.', 'WD Black SN850X - 2TB', 22999, 25, 5, 0, 1, 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?auto=format&fit=crop&w=800&q=80'),
('Seagate Barracuda 8TB', 'seagate-barracuda-8tb', 'Disque dur interne Seagate Barracuda 8TB pour stockage de masse.', 'Seagate Barracuda - 8TB', 14999, 30, 5, 0, 1, 'https://images.unsplash.com/photo-1563202750-43a89bef53b0?auto=format&fit=crop&w=800&q=80'),
('WD My Passport 5TB', 'wd-my-passport-5tb', 'Disque dur externe WD My Passport 5TB avec cryptage AES.', 'WD My Passport - 5TB', 12999, 40, 5, 0, 1, 'https://images.unsplash.com/photo-1563202750-43a89bef53b0?auto=format&fit=crop&w=800&q=80'),
('Samsung T7 Shield', 'samsung-t7-shield', 'SSD externe Samsung T7 Shield 2TB robuste et performant.', 'Samsung T7 Shield - 2TB', 19999, 35, 5, 1, 1, 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?auto=format&fit=crop&w=800&q=80'),
('SanDisk Extreme Pro 1TB', 'sandisk-extreme-pro-1tb', 'Clé USB SanDisk Extreme Pro 1TB avec vitesses USB 3.2.', 'SanDisk Extreme Pro - 1TB', 8999, 50, 5, 0, 1, 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=800&q=80'),
('LaCie Rugged SSD', 'lacie-rugged-ssd', 'SSD externe LaCie Rugged 2TB anti-choc pour les professionnels.', 'LaCie Rugged - 2TB', 29999, 15, 5, 0, 1, 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?auto=format&fit=crop&w=800&q=80'),
('Crucial X10 Pro', 'crucial-x10-pro', 'SSD externe Crucial X10 Pro 2TB compact et ultra-rapide.', 'Crucial X10 Pro - 2TB', 17999, 25, 5, 0, 1, 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?auto=format&fit=crop&w=800&q=80'),
('Synology DS920+', 'synology-ds920', 'NAS Synology DS920+ pour stockage réseau professionnel.', 'Synology DS920+', 44999, 8, 5, 1, 1, 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80'),
('Sabrent Rocket 4TB', 'sabrent-rocket-4tb', 'SSD NVMe Sabrent Rocket 4TB avec散热片 intégré.', 'Sabrent Rocket - 4TB', 49999, 10, 5, 1, 1, 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?auto=format&fit=crop&w=800&q=80');

-- =============================================
-- PRODUITS - AUDIO (10 produits)
-- =============================================

INSERT INTO products (name, slug, description, short_description, price, stock, category_id, is_featured, is_active, image) VALUES
('Sony WH-1000XM5', 'sony-wh-1000xm5', 'Casque Sony WH-1000XM5 avec réduction de bruit active premium.', 'Sony WH-1000XM5 - Noir', 29999, 25, 6, 1, 1, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80'),
('Apple AirPods Max', 'apple-airpods-max', 'Casque Apple AirPods Max avec son Spatial et réduction de bruit.', 'Apple AirPods Max - Gris sidéral', 59999, 12, 6, 1, 1, 'https://images.unsplash.com/photo-1613040809024-b4ef7ba99bc3?auto=format&fit=crop&w=800&q=80'),
('Bose QuietComfort Ultra', 'bose-quietcomfort-ultra', 'Casque Bose QC Ultra avec immersion audio spatiale.', 'Bose QuietComfort Ultra', 34999, 15, 6, 1, 1, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80'),
('Sennheiser Momentum 4', 'sennheiser-momentum-4', 'Casque Sennheiser Momentum 4 avec son haute fidélité.', 'Sennheiser Momentum 4', 27999, 18, 6, 0, 1, 'https://images.unsplash.com/photo-1583394838336-acd977736f90?auto=format&fit=crop&w=800&q=80'),
('Samsung Galaxy Buds3 Pro', 'samsung-galaxy-buds3-pro', 'Écouteurs Samsung Galaxy Buds3 Pro avec ANC adaptatif.', 'Samsung Galaxy Buds3 Pro', 19999, 30, 6, 1, 1, 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=800&q=80'),
('JBL PartyBox 310', 'jbl-partybox-310', 'Enceinte portable JBL PartyBox 310 pour des fiestas mémorables.', 'JBL PartyBox 310', 29999, 20, 6, 0, 1, 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?auto=format&fit=crop&w=800&q=80'),
('Sonos Era 300', 'sonos-era-300', 'Enceinte Sonos Era 300 avec son Spatial et Alexa intégrée.', 'Sonos Era 300', 44999, 10, 6, 1, 1, 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?auto=format&fit=crop&w=800&q=80'),
('Audio-Technica ATH-M50x', 'audio-technica-ath-m50x', 'Casque studio Audio-Technica ATH-M50x pour les audiophiles.', 'Audio-Technica ATH-M50x', 12999, 25, 6, 0, 1, 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&w=800&q=80'),
('Shure AONIC 50', 'shure-aonic-50', 'Casque Shure AONIC 50 avec réduction de bruit adjustable.', 'Shure AONIC 50', 27999, 15, 6, 0, 1, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80'),
('Bang & Olufsen Beoplay H95', 'bang-olufsen-h95', 'Casque premium Bang & Olufsen Beoplay H95 en édition limitée.', 'B&O Beoplay H95', 44999, 5, 6, 1, 1, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80');

-- =============================================
-- PRODUITS - RÉSEAUX (10 produits)
-- =============================================

INSERT INTO products (name, slug, description, short_description, price, stock, category_id, is_featured, is_active, image) VALUES
('ASUS RT-BE96U', 'asus-rt-be96u', 'Routeur Wi-Fi 7 ASUS RT-BE96U avec vitesses extrêmes.', 'ASUS RT-BE96U - Wi-Fi 7', 79999, 8, 7, 1, 1, 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=800&q=80'),
('TP-Link Archer AXE300', 'tp-link-archer-axe300', 'Routeur TP-Link AXE300 quad-band avec Wi-Fi 6E.', 'TP-Link Archer AXE300', 39999, 15, 7, 0, 1, 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=800&q=80'),
('Netgear Nighthawk RAXE500', 'netgear-nighthawk-raxe500', 'Routeur Netgear Nighthawk AXE500 Wi-Fi 6E Gaming.', 'Netgear RAXE500', 44999, 10, 7, 1, 1, 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=800&q=80'),
('Ubiquiti UniFi 6 Pro', 'ubiquiti-uniFi-6-pro', 'Point d accès Ubiquiti UniFi 6 Pro pour réseau professionnel.', 'Ubiquiti U6-Pro', 8999, 40, 7, 0, 1, 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=800&q=80'),
('TP-Link RE700X', 'tp-link-re700x', 'Répéteur Wi-Fi TP-Link RE700X pour étendre votre couverture.', 'TP-Link RE700X', 5999, 50, 7, 0, 1, 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=800&q=80'),
('Netgear GS108', 'netgear-gs108', 'Switch Netgear GS108 8 ports Gigabit pour petit bureau.', 'Netgear GS108 - 8 ports', 3999, 60, 7, 0, 1, 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=800&q=80'),
('Cisco SG350-28', 'cisco-sg350-28', 'Switch manageable Cisco SG350-28 pour entreprise.', 'Cisco SG350-28', 24999, 12, 7, 0, 1, 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=800&q=80'),
('ASUS PCE-AXE59BT', 'asus-pce-axe59bt', 'Carte Wi-Fi PCIe ASUS avec Bluetooth 5.2.', 'ASUS PCE-AXE59BT', 4999, 45, 7, 0, 1, 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=800&q=80'),
('TP-Link TL-WR902AC', 'tp-link-wr902ac', 'Routeur旅行 TP-Link WR902AC compact et polyvalant.', 'TP-Link WR902AC', 2999, 70, 7, 0, 1, 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?auto=format&fit=crop&w=800&q=80'),
('Ubiquiti EdgeRouter X', 'ubiquiti-edgerouter-x', 'Routeur Ubiquiti EdgeRouter X pour configuration avancee.', 'Ubiquiti EdgeRouter X', 4999, 35, 7, 0, 1, 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=800&q=80');

-- =============================================
-- PRODUITS - ACCESSOIRES (10 produits)
-- =============================================

INSERT INTO products (name, slug, description, short_description, price, stock, category_id, is_featured, is_active, image) VALUES
('CalDigit TS4', 'caldigit-ts4', 'Station d accueil CalDigit TS4 avec 18 ports et Thunderbolt 4.', 'CalDigit TS4 - 18 ports', 39999, 10, 8, 1, 1, 'https://images.unsplash.com/photo-1585771724684-38269d6639fd?auto=format&fit=crop&w=800&q=80'),
('Anker 777', 'anker-777', 'Station d ancrage Anker 777 avec 65W de puissance.', 'Anker 777 - 65W', 14999, 25, 8, 0, 1, 'https://images.unsplash.com/photo-1585771724684-38269d6639fd?auto=format&fit=crop&w=800&q=80'),
('Belkin USB-C Hub', 'belkin-usb-c-hub', 'Hub Belkin USB-C 6-en-1 avec HDMI 4K et lecteur SD.', 'Belkin USB-C Hub', 5999, 40, 8, 0, 1, 'https://images.unsplash.com/photo-1589840700256-41c5d84aff97?auto=format&fit=crop&w=800&q=80'),
('Samsung T7 Shield 1TB', 'samsung-t7-shield-1tb', 'SSD externe Samsung T7 Shield 1TB anti-choc et résistant.', 'Samsung T7 Shield - 1TB', 9999, 50, 8, 0, 1, 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?auto=format&fit=crop&w=800&q=80'),
('Apple MagSafe Battery', 'apple-magsafe-battery', 'Batterie externe Apple MagSafe pour iPhone.', 'Apple MagSafe Battery', 7999, 30, 8, 0, 1, 'https://images.unsplash.com/photo-1585771724684-38269d6639fd?auto=format&fit=crop&w=800&q=80'),
('Anker 737', 'anker-737', 'Batterie externe Anker 737 Power Bank 24000mAh 140W.', 'Anker 737 - 140W', 12999, 25, 8, 1, 1, 'https://images.unsplash.com/photo-1585771724684-38269d6639fd?auto=format&fit=crop&w=800&q=80'),
('UGREEN HDMI 2.1 3m', 'ugreen-hdmi-2-1', 'Câble HDMI UGREEN 8K 3 mètres haute vitesse.', 'UGREEN HDMI 2.1 - 3m', 1999, 100, 8, 0, 1, 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=800&q=80'),
('Native Union Stow', 'native-union-stow', 'Sacoche Native Union Stow pour laptop 15"', 'Native Union Stow - Gris', 6999, 35, 8, 0, 1, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80'),
('Peak Design Tech Pouch', 'peak-design-tech-pouch', 'Trousse Peak Design Tech Pouch pour organiser vos accessoires.', 'Peak Design Tech Pouch', 4999, 30, 8, 0, 1, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80'),
('Elago W4 Stand', 'elago-w4-stand', 'Support Apple Watch Elago W4 en silicone.', 'Elago W4 Stand', 1999, 80, 8, 0, 1, 'https://images.unsplash.com/photo-1551816230-ef5deaed4a26?auto=format&fit=crop&w=800&q=80');

