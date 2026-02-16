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
    public $alt_text;
    public $display_order;
    public $is_active;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Get all gallery images
     * @param string|null $category Filter by category
     * @return array
     */
    public function getAll($category = null)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_active = 1";

        if ($category && $category !== 'all') {
            $query .= " AND category = :category";
        }

        $query .= " ORDER BY display_order ASC, created_at DESC";

        $stmt = $this->conn->prepare($query);

        if ($category && $category !== 'all') {
            $stmt->bindParam(':category', $category);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get single image by ID
     * @param int $id
     * @return array|false
     */
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Create new gallery image
     * @return int|false Returns insert ID on success, false on failure
     */
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                  (filename, original_name, category, alt_text, display_order, is_active) 
                  VALUES (:filename, :original_name, :category, :alt_text, :display_order, :is_active)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':filename', $this->filename);
        $stmt->bindParam(':original_name', $this->original_name);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':alt_text', $this->alt_text);
        $stmt->bindParam(':display_order', $this->display_order);
        $stmt->bindParam(':is_active', $this->is_active);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    /**
     * Update gallery image
     * @return bool
     */
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET category = :category, 
                      alt_text = :alt_text, 
                      display_order = :display_order,
                      is_active = :is_active
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':alt_text', $this->alt_text);
        $stmt->bindParam(':display_order', $this->display_order);
        $stmt->bindParam(':is_active', $this->is_active);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    /**
     * Delete gallery image (soft delete)
     * @return bool
     */
    public function delete()
    {
        // Soft delete - set is_active to 0
        $query = "UPDATE " . $this->table_name . " SET is_active = 0 WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    /**
     * Hard delete - permanently remove from database
     * @return bool
     */
    public function hardDelete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    /**
     * Get next display order value
     * @return int
     */
    public function getNextDisplayOrder()
    {
        $query = "SELECT MAX(display_order) as max_order FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch();
        return ($row['max_order'] ?? 0) + 1;
    }
}
