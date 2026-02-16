<?php
/**
 * Authentication Controller
 * Handles user login, logout, and session verification
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';

$method = $_SERVER['REQUEST_METHOD'];

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

/**
 * Handle user login
 */
function login()
{
    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->username) || empty($data->password)) {
        sendErrorResponse('Username and password are required', 400);
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
