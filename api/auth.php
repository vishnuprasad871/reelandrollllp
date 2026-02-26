<?php
/**
 * Authentication Controller
 * Handles user login, logout, and session verification
 */

// Buffer all output so stray warnings/notices don't corrupt JSON
ob_start();

// Global exception handler — catches fatal errors even before config is loaded
set_exception_handler(function (Throwable $e) {
    ob_clean();
    http_response_code(500);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    exit();
});

try {
    require_once __DIR__ . '/config/config.php';
    require_once __DIR__ . '/models/User.php';
} catch (Throwable $e) {
    ob_clean();
    http_response_code(500);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['error' => 'Startup error: ' . $e->getMessage()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'POST':
            login();
            break;
        case 'GET':
            verify();
            break;
        default:
            sendErrorResponse('Method not allowed', 405);
    }
} catch (Throwable $e) {
    error_log('auth.php error: ' . $e->getMessage());
    ob_clean();
    sendErrorResponse('Server error: ' . $e->getMessage(), 500);
}

/**
 * Handle user login
 */
function login()
{
    $rawInput = file_get_contents("php://input");
    $data = json_decode($rawInput);

    // Guard: null body or invalid JSON
    if ($data === null || !isset($data->username, $data->password)) {
        sendErrorResponse('Username and password are required', 400);
        return;
    }

    if (empty($data->username) || empty($data->password)) {
        sendErrorResponse('Username and password are required', 400);
        return;
    }

    $user = new User();

    if ($user->authenticate($data->username, $data->password)) {
        $user->setSession();

        sendJsonResponse([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email
            ]
        ]);
    } else {
        sendErrorResponse('Invalid username or password', 401);
    }
}

/**
 * Verify current session
 */
function verify()
{
    if (User::isAuthenticated()) {
        sendJsonResponse([
            'authenticated' => true,
            'user' => [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username']
            ]
        ]);
    } else {
        sendJsonResponse([
            'authenticated' => false
        ]);
    }
}
