<?php
/**
 * Gallery Controller
 * Handles gallery CRUD + bulk operations
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Gallery.php';
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/helpers/ImageOptimizer.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// Bulk operations are POST with ?action=bulk_assign or ?action=bulk_delete
if ($method === 'POST' && in_array($action, ['bulk_assign', 'bulk_delete'])) {
    handleBulkOperation($action);
    exit;
}

switch ($method) {
    case 'GET':    getGallery();        break;
    case 'POST':   createGalleryItem(); break;
    case 'PUT':    updateGalleryItem(); break;
    case 'DELETE': deleteGalleryItem(); break;
    default:       sendErrorResponse('Method not allowed', 405);
}

// ─── Helpers ───────────────────────────────────────────────────────────────

function buildImageUrl($item)
{
    if (strpos($item['filename'], 'gallery_') === 0) {
        $item['image_url'] = APP_URL . '/uploads/gallery/' . $item['filename'];
    } else {
        $item['image_url'] = APP_URL . '/assets/' . $item['filename'];
    }
    return $item;
}

// ─── Handlers ──────────────────────────────────────────────────────────────

function getGallery()
{
    $category = $_GET['category'] ?? null;
    $group_id = isset($_GET['group_id']) ? (int)$_GET['group_id'] : null;

    $gallery = new Gallery();
    $items   = $gallery->getAll($category, $group_id);
    $items   = array_map('buildImageUrl', $items);

    sendJsonResponse([
        'success' => true,
        'data'    => $items,
        'count'   => count($items)
    ]);
}

function createGalleryItem()
{
    if (!User::isAuthenticated()) sendErrorResponse('Authentication required', 401);

    if (!isset($_FILES['image'])) sendErrorResponse('No image provided', 400);

    $file         = $_FILES['image'];
    $category     = $_POST['category']     ?? '';
    $alt_text     = $_POST['alt_text']     ?? '';
    $group_id     = !empty($_POST['group_id'])  ? (int)$_POST['group_id']  : null;
    $sort_order   = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;

    $valid_categories = ['wedding', 'portrait', 'event', 'landscape', 'sports'];
    // Category is internal-only; default to 'wedding' if not provided
    if (!in_array($category, $valid_categories)) $category = 'wedding';

    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, ALLOWED_EXTENSIONS)) {
        sendErrorResponse('Invalid file type. Allowed: ' . implode(', ', ALLOWED_EXTENSIONS), 400);
    }
    if ($file['size'] > UPLOAD_MAX_SIZE) sendErrorResponse('File size exceeds limit', 400);

    $filename    = uniqid('gallery_') . '_' . time() . '.' . $file_extension;
    $target_path = UPLOAD_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $target_path)) {
        $detail = '';
        if (!file_exists(UPLOAD_DIR))   $detail .= 'Upload dir missing. ';
        elseif (!is_writable(UPLOAD_DIR)) $detail .= 'Upload dir not writable. ';
        if ($file['error'] !== UPLOAD_ERR_OK) $detail .= 'PHP error: ' . $file['error'];
        sendErrorResponse('Failed to upload image: ' . $detail, 500);
    }

    // Resize to max 1920px and convert to WebP if GD supports it
    $filename = ImageOptimizer::optimize($target_path, $filename);

    $gallery                = new Gallery();
    $gallery->filename      = $filename;
    $gallery->original_name = $file['name'];
    $gallery->category      = $category;
    $gallery->group_id      = $group_id;
    $gallery->sort_order    = $sort_order;
    $gallery->alt_text      = $alt_text;
    $gallery->display_order = $gallery->getNextDisplayOrder();
    $gallery->is_active     = 1;

    $id = $gallery->create();
    if ($id) {
        sendJsonResponse([
            'success'   => true,
            'message'   => 'Image uploaded successfully',
            'data'      => [
                'id'        => $id,
                'filename'  => $filename,
                'image_url' => APP_URL . '/uploads/gallery/' . $filename
            ]
        ], 201);
    }

    @unlink(UPLOAD_DIR . $filename);
    sendErrorResponse('Failed to save image data', 500);
}

function updateGalleryItem()
{
    if (!User::isAuthenticated()) sendErrorResponse('Authentication required', 401);

    $data = json_decode(file_get_contents('php://input'));
    if (empty($data->id)) sendErrorResponse('Image ID is required', 400);

    $gallery  = new Gallery();
    $existing = $gallery->getById($data->id);
    if (!$existing) sendErrorResponse('Image not found', 404);

    $valid_categories = ['wedding', 'portrait', 'event', 'landscape', 'sports'];
    $newCategory = $data->category ?? $existing['category'];
    if (!in_array($newCategory, $valid_categories)) sendErrorResponse('Invalid category', 400);

    $gallery->id            = $data->id;
    $gallery->category      = $newCategory;
    $gallery->group_id      = isset($data->group_id)    ? ($data->group_id === '' ? null : (int)$data->group_id) : $existing['group_id'];
    $gallery->sort_order    = isset($data->sort_order)  ? (int)$data->sort_order  : $existing['sort_order'];
    $gallery->alt_text      = $data->alt_text      ?? $existing['alt_text'];
    $gallery->display_order = $data->display_order ?? $existing['display_order'];
    $gallery->is_active     = $data->is_active     ?? $existing['is_active'];

    if ($gallery->update()) {
        sendJsonResponse(['success' => true, 'message' => 'Image updated successfully']);
    }
    sendErrorResponse('Failed to update image', 500);
}

function deleteGalleryItem()
{
    if (!User::isAuthenticated()) sendErrorResponse('Authentication required', 401);

    $id = $_GET['id'] ?? null;
    if (empty($id)) sendErrorResponse('Image ID is required', 400);

    $gallery  = new Gallery();
    $existing = $gallery->getById($id);
    if (!$existing) sendErrorResponse('Image not found', 404);

    $gallery->id = $id;
    if ($gallery->delete()) {
        sendJsonResponse(['success' => true, 'message' => 'Image deleted successfully']);
    }
    sendErrorResponse('Failed to delete image', 500);
}

function handleBulkOperation($action)
{
    if (!User::isAuthenticated()) sendErrorResponse('Authentication required', 401);

    $data = json_decode(file_get_contents('php://input'));
    if (empty($data->ids) || !is_array($data->ids)) sendErrorResponse('Image IDs array required', 400);

    $ids = array_map('intval', $data->ids);
    $gallery = new Gallery();

    if ($action === 'bulk_assign') {
        $group_id = isset($data->group_id) ? ($data->group_id === '' ? null : (int)$data->group_id) : null;
        if ($gallery->bulkAssignGroup($ids, $group_id)) {
            sendJsonResponse(['success' => true, 'message' => count($ids) . ' image(s) assigned']);
        }
        sendErrorResponse('Bulk assign failed', 500);
    }

    if ($action === 'bulk_delete') {
        if ($gallery->bulkDelete($ids)) {
            sendJsonResponse(['success' => true, 'message' => count($ids) . ' image(s) deleted']);
        }
        sendErrorResponse('Bulk delete failed', 500);
    }
}
