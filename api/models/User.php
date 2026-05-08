<?php
/**
 * User Model
 * Handles admin authentication and user management
 */

require_once __DIR__ . '/../config/database.php';

class User
{
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;
    public $email;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Authenticate user
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function authenticate($username, $password)
    {
        $query = "SELECT id, username, password, email FROM " . $this->table_name . " WHERE username = :username LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();

            if (password_verify($password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->email = $row['email'];
                return true;
            }
        }

        return false;
    }

    /**
     * Create new user
     * @return bool
     */
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " (username, password, email) VALUES (:username, :password, :email)";

        $stmt = $this->conn->prepare($query);

        // Hash password
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $this->email);

        return $stmt->execute();
    }

    /**
     * Check if user is authenticated via session
     * @return bool
     */
    public static function isAuthenticated()
    {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Get current authenticated user ID
     * @return int|null
     */
    public static function getCurrentUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Set user session
     */
    public function setSession()
    {
        $_SESSION['user_id'] = $this->id;
        $_SESSION['username'] = $this->username;
    }

    /**
     * Destroy user session
     */
    public static function destroySession()
    {
        session_unset();
        session_destroy();
    }
}
