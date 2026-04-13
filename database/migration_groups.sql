-- ============================================================
-- Gallery Groups Migration
-- Run this ONCE on the server database
-- ============================================================

-- 1. Create gallery_groups table
CREATE TABLE IF NOT EXISTS gallery_groups (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    title         VARCHAR(150) NOT NULL,
    cover_image_id INT NULL DEFAULT NULL,
    sort_order    INT DEFAULT 0,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Add group_id to gallery table (ignore error if column already exists)
ALTER TABLE gallery
    ADD COLUMN group_id  INT NULL DEFAULT NULL AFTER category,
    ADD COLUMN sort_order INT DEFAULT 0         AFTER group_id,
    ADD INDEX idx_group_id (group_id);
