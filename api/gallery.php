<?php
/**
 * Gallery Controller
 * Handles gallery CRUD operations
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Gallery.php';
require_once __DIR__ . '/models/User.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getGallery();
        break;
    case 'POST':
        createGalleryItem();
        break;
    case 'PUT':
        updateGalleryItem();
        break;
    case 'DELETE':
        deleteGalleryItem();
        break;
    default:
        sendErrorResponse('Method not allowed', 405);
}

/**
 * Get all gallery images or filter by category
 */
function getGallery()
{
    $category = $_GET['category'] ?? null;

    $gallery = new Gallery();
    $items = $gallery->getAll($category);

    // Add full URL to images
    $baseUrl = APP_URL . '/assets/';
    $items = array_map(function ($item) use ($baseUrl) {
        $item['image_url'] = $baseUrl . $item['filename'];
        return $item;
    }, $items);

    sendJsonResponse([
        'success' => true,
        'data' => $items,
        'count' => count($items)
    ]);
}

/**
 * Create new gallery item (admin only)
 */
function createGalleryItem()
{
    // Check authentication
    if (!User::isAuthenticated()) {
        sendErrorResponse('Authentication required', 401);
    }

    // Handle file upload
    if (!isset($_FILES['image'])) {
        sendErrorResponse('No image provided', 400);
    }

    $file = $_FILES['image'];
    $category = $_POST['category'] ?? '';
    $alt_text = $_POST['alt_text'] ?? '';
    $display_order = $_POST['display_order'] ?? null;

    // Validate category
    $valid_categories = ['wedding', 'portrait', 'event', 'landscape'];
    if (!in_array($category, $valid_categories)) {
        sendErrorResponse('Invalid category', 400);
    }

    // Validate file
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, ALLOWED_EXTENSIONS)) {
        sendErrorResponse('Invalid file type. Allowed: ' . implode(', ', ALLOWED_EXTENSIONS), 400);
    }

    if ($file['size'] > UPLOAD_MAX_SIZE) {
        sendErrorResponse('File size exceeds maximum allowed size', 400);
    }

    // Generate unique filename
    $filename = uniqid('gallery_') . '_' . time() . '.' . $file_extension;
    $target_path = UPLOAD_DIR . $filename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $target_path)) {
        sendErrorResponse('Failed to upload image', 500);
    }

    // Save to database
    $gallery = new Gallery();
    $gallery->filename = $filename;
    $gallery->original_name = $file['name'];
    $gallery->category = $category;
    $gallery->alt_text = $alt_text;
    $gallery->display_order = $display_order ?? $gallery->getNextDisplayOrder();
    $gallery->is_active = 1;

    $id = $gallery->create();

    if ($id) {
        sendJsonResponse([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'data' => [
                'id' => $id,
                'filename' => $filename,
                'image_url' => APP_URL . '/uploads/gallery/' . $filename
            ]
        ], 201);
    } else {
        // Remove uploaded file if database insert fails
        unlink($target_path);
        sendErrorResponse('Failed to save image data', 500);
    }
}

/**
 * Update gallery item (admin only)
 */
function updateGalleryItem()
{
    // Check authentication
    if (!User::isAuthenticated()) {
        sendErrorResponse('Authentication required', 401);
    }

    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->id)) {
        sendErrorResponse('Image ID is required', 400);
    }

    $gallery = new Gallery();
    $existing = $gallery->getById($data->id);

    if (!$existing) {
        sendErrorResponse('Image not found', 404);
    }

    // Validate category if provided
    if (isset($data->category)) {
        $valid_categories = ['wedding', 'portrait', 'event', 'landscape'];
        if (!in_array($data->category, $valid_categories)) {
            sendErrorResponse('Invalid category', 400);
        }
        $gallery->category = $data->category;
    } else {
        $gallery->category = $existing['category'];
    }

    $gallery->id = $data->id;
    $gallery->alt_text = $data->alt_text ?? $existing['alt_text'];
    $gallery->display_order = $data->display_order ?? $existing['display_order'];
    $gallery->is_active = $data->is_active ?? $existing['is_active'];

    if ($gallery->update()) {
        sendJsonResponse([
            'success' => true,
            'message' => 'Image updated successfully'
        ]);
    } else {
        sendErrorResponse('Failed to update image', 500);
    }
}

/**
 * Delete gallery item (admin only)
 */
function deleteGalleryItem()
{
    // Check authentication
    if (!User::isAuthenticated()) {
        sendErrorResponse('Authentication required', 401);
    }

    $id = $_GET['id'] ?? null;

    if (empty($id)) {
        sendErrorResponse('Image ID is required', 400);
    }

    $gallery = new Gallery();
    $existing = $gallery->getById($id);

    if (!$existing) {
        sendErrorResponse('Image not found', 404);
    }

    $gallery->id = $id;

    if ($gallery->delete()) {
        // Optionally delete physical file
        // $file_path = UPLOAD_DIR . $existing['filename'];
        // if (file_exists($file_path)) {
        //     unlink($file_path);
        // }

        sendJsonResponse([
            'success' => true,
            'message' => 'Image deleted successfully'
        ]);
    } else {
        sendErrorResponse('Failed to delete image', 500);
    }
}
