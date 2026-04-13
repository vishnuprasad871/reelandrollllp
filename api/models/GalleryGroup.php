<?php
/**
 * GalleryGroup Model
 * Handles gallery group CRUD operations
 */

require_once __DIR__ . '/../config/database.php';

class GalleryGroup
{
    private $conn;
    private $table = 'gallery_groups';

    public $id;
    public $title;
    public $cover_image_id;
    public $sort_order;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Get all groups with image count and cover image filename
     */
    public function getAll()
    {
        try {
            // Use cover_image if set, otherwise fall back to the first image in the group
            $query = "SELECT g.*,
                             COUNT(gi.id) AS image_count,
                             COALESCE(gi_cover.filename, gi_first.filename) AS cover_filename,
                             COALESCE(gi_cover.id, gi_first.id) AS resolved_cover_id
                      FROM {$this->table} g
                      LEFT JOIN gallery gi
                             ON gi.group_id = g.id AND gi.is_active = 1
                      LEFT JOIN gallery gi_cover
                             ON gi_cover.id = g.cover_image_id AND gi_cover.is_active = 1
                      LEFT JOIN gallery gi_first
                             ON gi_first.id = (
                                 SELECT id FROM gallery
                                 WHERE group_id = g.id AND is_active = 1
                                 ORDER BY sort_order ASC, created_at ASC
                                 LIMIT 1
                             )
                      GROUP BY g.id
                      ORDER BY g.sort_order ASC, g.created_at ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            // Table may not exist yet — return empty array
            return [];
        }
    }

    /**
     * Get single group by ID
     */
    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create new group
     */
    public function create()
    {
        $query = "INSERT INTO {$this->table} (title, cover_image_id, sort_order)
                  VALUES (:title, :cover_image_id, :sort_order)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title',          $this->title);
        $stmt->bindParam(':cover_image_id', $this->cover_image_id, PDO::PARAM_INT);
        $stmt->bindParam(':sort_order',     $this->sort_order,     PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Update group
     */
    public function update()
    {
        $query = "UPDATE {$this->table}
                  SET title = :title,
                      cover_image_id = :cover_image_id,
                      sort_order = :sort_order
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title',          $this->title);
        $stmt->bindParam(':cover_image_id', $this->cover_image_id, PDO::PARAM_INT);
        $stmt->bindParam(':sort_order',     $this->sort_order,     PDO::PARAM_INT);
        $stmt->bindParam(':id',             $this->id,             PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Delete group (unassigns images first)
     */
    public function delete()
    {
        // Unassign all images from this group
        $unassign = $this->conn->prepare("UPDATE gallery SET group_id = NULL WHERE group_id = :id");
        $unassign->bindParam(':id', $this->id, PDO::PARAM_INT);
        $unassign->execute();

        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt  = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Get the next sort_order value
     */
    public function getNextSortOrder()
    {
        $stmt = $this->conn->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 AS next_order FROM {$this->table}");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['next_order'];
    }
}
