-- ============================================================
-- Gallery Groups Migration  (MySQL 8.0 compatible, safe to re-run)
-- ============================================================

-- 1. Create gallery_groups table
CREATE TABLE IF NOT EXISTS gallery_groups (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    title          VARCHAR(150) NOT NULL,
    cover_image_id INT NULL DEFAULT NULL,
    sort_order     INT DEFAULT 0,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Add columns to gallery table conditionally (MySQL 8.0 safe)
DROP PROCEDURE IF EXISTS rnr_migrate_gallery;

DELIMITER //
CREATE PROCEDURE rnr_migrate_gallery()
BEGIN
    -- Add group_id if missing
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'gallery'
          AND COLUMN_NAME  = 'group_id'
    ) THEN
        ALTER TABLE gallery ADD COLUMN group_id INT NULL DEFAULT NULL AFTER category;
    END IF;

    -- Add sort_order if missing
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'gallery'
          AND COLUMN_NAME  = 'sort_order'
    ) THEN
        ALTER TABLE gallery ADD COLUMN sort_order INT DEFAULT 0;
    END IF;

    -- Add index if missing
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME   = 'gallery'
          AND INDEX_NAME   = 'idx_group_id'
    ) THEN
        ALTER TABLE gallery ADD INDEX idx_group_id (group_id);
    END IF;
END //
DELIMITER ;

CALL rnr_migrate_gallery();
DROP PROCEDURE IF EXISTS rnr_migrate_gallery;
