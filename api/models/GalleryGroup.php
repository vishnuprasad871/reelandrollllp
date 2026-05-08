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
            // 1. Fetch all groups
            $stmt = $this->conn->prepare(
                "SELECT * FROM {$this->table} ORDER BY sort_order ASC, created_at ASC"
            );
            $stmt->execute();
            $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($groups as &$group) {
                $gid = (int)$group['id'];

                // 2. Image count (safe — group_id column may not exist yet)
                try {
                    $c = $this->conn->prepare(
                        "SELECT COUNT(*) FROM gallery WHERE group_id = ? AND is_active = 1"
                    );
                    $c->execute([$gid]);
                    $group['image_count'] = (int)$c->fetchColumn();
                } catch (\Exception $e) {
                    $group['image_count'] = 0;
                }

                // 3. Cover filename — explicit cover first, then first image in group
                $group['cover_filename'] = null;

                if (!empty($group['cover_image_id'])) {
                    $cv = $this->conn->prepare(
                        "SELECT filename FROM gallery WHERE id = ? AND is_active = 1 LIMIT 1"
                    );
                    $cv->execute([(int)$group['cover_image_id']]);
                    $group['cover_filename'] = $cv->fetchColumn() ?: null;
                }

                if (!$group['cover_filename']) {
                    try {
                        $fi = $this->conn->prepare(
                            "SELECT filename FROM gallery WHERE group_id = ? AND is_active = 1 ORDER BY created_at ASC LIMIT 1"
                        );
                        $fi->execute([$gid]);
                        $group['cover_filename'] = $fi->fetchColumn() ?: null;
                    } catch (\Exception $e) {
                        // group_id column not yet present — no cover
                    }
                }
            }

            return $groups;
        } catch (\Exception $e) {
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
