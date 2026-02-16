-- Seed data for Reel and Roll Photography Gallery

USE reelandroll;

-- Insert default admin user
-- Username: admin
-- Password: admin123 (hashed with PASSWORD_DEFAULT)
INSERT INTO users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@reelandroll.com')
ON DUPLICATE KEY UPDATE username = username;

-- Migrate existing gallery images from the current HTML
INSERT INTO gallery (filename, original_name, category, alt_text, display_order, is_active) VALUES
('portfolio_wedding_1770402245749.png', 'Wedding Photography 1', 'wedding', 'Wedding Photography', 1, 1),
('portfolio_portrait_1770402268053.png', 'Portrait Photography 1', 'portrait', 'Portrait Photography', 2, 1),
('about_photographer_1770402230073.png', 'Professional Portrait', 'portrait', 'Professional Portrait', 3, 1),
('portfolio_landscape_1770402286898.png', 'Landscape Photography 1', 'landscape', 'Landscape Photography', 4, 1),
('hero_background_1770402213477.png', 'Sunset Photography', 'landscape', 'Sunset Photography', 5, 1),
('portfolio_event_1770402304065.png', 'Event Photography 1', 'event', 'Event Photography', 6, 1)
ON DUPLICATE KEY UPDATE filename = filename;
