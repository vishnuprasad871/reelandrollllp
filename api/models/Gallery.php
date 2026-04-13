<?php
/**
 * Gallery Model
 * Handles gallery image CRUD operations
 */

require_once __DIR__ . '/../config/database.php';

class Gallery
{
    private $conn;
    private $table_name = "gallery";

    public $id;
    public $filename;
    public $original_name;
    public $category;
    public $group_id;
    public $sort_order;
    public $alt_text;
    public $display_order;
    public $is_active;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Get all gallery images, optionally filtered by category and/or group_id
     */
    public function getAll($category = null, $group_id = null)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE is_active = 1";

        if ($category && $category !== 'all') {
            $query .= " AND category = :category";
        }

        if ($group_id !== null) {
            $query .= " AND group_id = :group_id";
        }

        $query .= " ORDER BY sort_order ASC, display_order ASC, created_at DESC";

        $stmt = $this->conn->prepare($query);

        if ($category && $category !== 'all') {
            $stmt->bindParam(':category', $category);
        }
        if ($group_id !== null) {
            $stmt->bindParam(':group_id', $group_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get single image by ID
     */
    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE id = :id LIMIT 1";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create new gallery image
     */
    public function create()
    {
        $query = "INSERT INTO {$this->table_name}
                  (filename, original_name, category, group_id, sort_order, alt_text, display_order, is_active)
                  VALUES
                  (:filename, :original_name, :category, :group_id, :sort_order, :alt_text, :display_order, :is_active)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':filename',      $this->filename);
        $stmt->bindParam(':original_name', $this->original_name);
        $stmt->bindParam(':category',      $this->category);
        $stmt->bindParam(':group_id',      $this->group_id,      PDO::PARAM_INT);
        $stmt->bindParam(':sort_order',    $this->sort_order,    PDO::PARAM_INT);
        $stmt->bindParam(':alt_text',      $this->alt_text);
        $stmt->bindParam(':display_order', $this->display_order, PDO::PARAM_INT);
        $stmt->bindParam(':is_active',     $this->is_active,     PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Update gallery image
     */
    public function update()
    {
        $query = "UPDATE {$this->table_name}
                  SET category      = :category,
                      group_id      = :group_id,
                      sort_order    = :sort_order,
                      alt_text      = :alt_text,
                      display_order = :display_order,
                      is_active     = :is_active
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category',      $this->category);
        $stmt->bindParam(':group_id',      $this->group_id,      PDO::PARAM_INT);
        $stmt->bindParam(':sort_order',    $this->sort_order,    PDO::PARAM_INT);
        $stmt->bindParam(':alt_text',      $this->alt_text);
        $stmt->bindParam(':display_order', $this->display_order, PDO::PARAM_INT);
        $stmt->bindParam(':is_active',     $this->is_active,     PDO::PARAM_INT);
        $stmt->bindParam(':id',            $this->id,            PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Soft delete — set is_active = 0
     */
    public function delete()
    {
        $query = "UPDATE {$this->table_name} SET is_active = 0 WHERE id = :id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    /**
     * Hard delete — permanently remove
     */
    public function hardDelete()
    {
        $query = "DELETE FROM {$this->table_name} WHERE id = :id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    /**
     * Bulk assign images to a group
     * @param int[] $ids
     * @param int|null $group_id  null = unassign
     */
    public function bulkAssignGroup(array $ids, $group_id)
    {
        if (empty($ids)) return false;

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "UPDATE {$this->table_name} SET group_id = ? WHERE id IN ({$placeholders}) AND is_active = 1";

        $stmt = $this->conn->prepare($query);
        $params = array_merge([$group_id], array_map('intval', $ids));
        return $stmt->execute($params);
    }

    /**
     * Bulk soft-delete images
     * @param int[] $ids
     */
    public function bulkDelete(array $ids)
    {
        if (empty($ids)) return false;

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "UPDATE {$this->table_name} SET is_active = 0 WHERE id IN ({$placeholders})";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute(array_map('intval', $ids));
    }

    /**
     * Get next display_order value
     */
    public function getNextDisplayOrder()
    {
        $query = "SELECT MAX(display_order) as max_order FROM {$this->table_name}";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row['max_order'] ?? 0) + 1;
    }
}
