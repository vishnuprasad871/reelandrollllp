<?php
/**
 * Application Configuration
 * General settings and constants
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting based on environment
$app_env = getenv('APP_ENV') ?: 'local';
if ($app_env === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Application constants
define('APP_ENV', $app_env);
define('APP_DEBUG', getenv('APP_DEBUG') === 'true');
define('APP_URL', getenv('APP_URL') ?: 'https://reelandroll.com');

// Upload configuration
define('UPLOAD_DIR', __DIR__ . '/../../uploads/gallery/');
define('UPLOAD_MAX_SIZE', getenv('UPLOAD_MAX_SIZE') ?: 5242880); // 5MB default
define('ALLOWED_EXTENSIONS', explode(',', getenv('ALLOWED_EXTENSIONS') ?: 'jpg,jpeg,png,gif,webp'));

// Create upload directory if it doesn't exist (may fail on some hosts — non-fatal)
if (!file_exists(UPLOAD_DIR)) {
    @mkdir(UPLOAD_DIR, 0755, true);
}

// CORS headers for API
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Max-Age: 3600');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

/**
 * Send JSON response
 * @param mixed $data
 * @param int $status_code
 */
function sendJsonResponse($data, $status_code = 200)
{
    if (ob_get_level() > 0)
        ob_clean(); // discard any buffered warnings/notices
    http_response_code($status_code);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data);
    exit();
}

/**
 * Send error response
 * @param string $message
 * @param int $status_code
 */
function sendErrorResponse($message, $status_code = 400)
{
    sendJsonResponse(['error' => $message], $status_code);
}
