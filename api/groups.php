<?php
/**
 * Gallery Groups Controller
 * Public GET — Admin POST/PUT/DELETE (auth required)
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/GalleryGroup.php';
require_once __DIR__ . '/models/User.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// PATCH /api/groups.php?action=set_cover
if ($method === 'PATCH' && $action === 'set_cover') {
    setCover();
    exit;
}

switch ($method) {
    case 'GET':    getGroups();    break;
    case 'POST':   createGroup();  break;
    case 'PUT':    updateGroup();  break;
    case 'DELETE': deleteGroup();  break;
    default:       sendErrorResponse('Method not allowed', 405);
}

// ─── Helpers ───────────────────────────────────────────────────────────────

function buildCoverUrl($filename)
{
    if (!$filename) return null;
    if (strpos($filename, 'gallery_') === 0) {
        return APP_URL . '/uploads/gallery/' . $filename;
    }
    return APP_URL . '/assets/' . $filename;
}

// ─── Handlers ──────────────────────────────────────────────────────────────

function getGroups()
{
    $group = new GalleryGroup();
    $items = $group->getAll();

    $items = array_map(function ($item) {
        $item['cover_url'] = buildCoverUrl($item['cover_filename'] ?? null);
        unset($item['cover_filename']);
        return $item;
    }, $items);

    sendJsonResponse(['success' => true, 'data' => $items]);
}

function createGroup()
{
    if (!User::isAuthenticated()) sendErrorResponse('Authentication required', 401);

    $data = json_decode(file_get_contents('php://input'));
    if (empty($data->title)) sendErrorResponse('Title is required', 400);

    $group = new GalleryGroup();
    $group->title          = trim($data->title);
    $group->cover_image_id = !empty($data->cover_image_id) ? (int)$data->cover_image_id : null;
    $group->sort_order     = isset($data->sort_order) ? (int)$data->sort_order : $group->getNextSortOrder();

    $id = $group->create();
    if ($id) {
        sendJsonResponse(['success' => true, 'message' => 'Group created', 'id' => (int)$id], 201);
    }
    sendErrorResponse('Failed to create group', 500);
}

function updateGroup()
{
    if (!User::isAuthenticated()) sendErrorResponse('Authentication required', 401);

    $data = json_decode(file_get_contents('php://input'));
    if (empty($data->id)) sendErrorResponse('ID is required', 400);

    $group    = new GalleryGroup();
    $existing = $group->getById((int)$data->id);
    if (!$existing) sendErrorResponse('Group not found', 404);

    $group->id             = (int)$data->id;
    $group->title          = isset($data->title)          ? trim($data->title)          : $existing['title'];
    $group->cover_image_id = isset($data->cover_image_id) ? (int)$data->cover_image_id  : ($existing['cover_image_id'] ?? null);
    $group->sort_order     = isset($data->sort_order)     ? (int)$data->sort_order      : $existing['sort_order'];

    if ($group->update()) {
        sendJsonResponse(['success' => true, 'message' => 'Group updated']);
    }
    sendErrorResponse('Failed to update group', 500);
}

function setCover()
{
    if (!User::isAuthenticated()) sendErrorResponse('Authentication required', 401);

    $data = json_decode(file_get_contents('php://input'));
    if (empty($data->group_id) || empty($data->image_id)) sendErrorResponse('group_id and image_id required', 400);

    $group    = new GalleryGroup();
    $existing = $group->getById((int)$data->group_id);
    if (!$existing) sendErrorResponse('Group not found', 404);

    $group->id             = (int)$data->group_id;
    $group->title          = $existing['title'];
    $group->cover_image_id = (int)$data->image_id;
    $group->sort_order     = $existing['sort_order'];

    if ($group->update()) {
        sendJsonResponse(['success' => true, 'message' => 'Cover image set']);
    }
    sendErrorResponse('Failed to set cover', 500);
}

function deleteGroup()
{
    if (!User::isAuthenticated()) sendErrorResponse('Authentication required', 401);

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if (!$id) sendErrorResponse('ID is required', 400);

    $group    = new GalleryGroup();
    $existing = $group->getById($id);
    if (!$existing) sendErrorResponse('Group not found', 404);

    $group->id = $id;
    if ($group->delete()) {
        sendJsonResponse(['success' => true, 'message' => 'Group deleted']);
    }
    sendErrorResponse('Failed to delete group', 500);
}
