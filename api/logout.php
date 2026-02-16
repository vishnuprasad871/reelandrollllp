<?php
/**
 * Logout Controller
 * Handles user logout
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    User::destroySession();

    sendJsonResponse([
        'success' => true,
        'message' => 'Logout successful'
    ]);
} else {
    sendErrorResponse('Method not allowed', 405);
}
