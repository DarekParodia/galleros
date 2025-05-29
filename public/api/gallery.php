<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, PATCH");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
require_once '../../php/_user.php';
require_once '../../php/_post.php';
require_once '../../php/_database.php';
require_once '../../php/_gallery.php';

function getGallery(int $q)
{
    global $db;
    $sql = "SELECT * FROM galleries WHERE id = $q";
    $row = $db->query($sql)[0];
    if (!$row) return null;
    $gallery = new Gallery($row['id']);
    return $gallery;
}

function getAllGalleries()
{
    global $db;
    $galleries = $db->getAll('galleries');
    $galleries = array_map(function ($gallery) {
        return new Gallery($gallery['id']);
    }, $galleries);
    return $galleries;
}

function createGallery(string $name, int $user_id)
{
    global $db;
    $sql = "INSERT INTO galleries (name, author) VALUES ('$name', $user_id)";
    $db->query($sql);
    if ($db->lastQuerrySuccessful()) {
        return new Gallery($db->lastInsertId());
    }
    return null;
}

function updateGallery(int $id, string $name)
{
    global $db;
    $sql = "UPDATE galleries SET name = '$name' WHERE id = $id";
    $db->query($sql);
    if ($db->lastQuerrySuccessful()) {
        return new Gallery($id);
    }
    return null;
}

function deleteGallery(int $id)
{
    global $db;
    $sql = "DELETE FROM galleries WHERE id = $id";
    $db->query($sql);
    return $db->lastQuerrySuccessful();
}

// ==========
// API
// ==========

// GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    if (isset($_GET['id'])) {
        $gallery = getGallery($_GET['id']);
        if ($gallery) {
            echo $gallery->toJSON();
        } else {
            echo json_encode(['error' => 'Gallery not found']);
        }
    } else {
        $galleries = getAllGalleries();
        $galleries_arr = array_map(function ($gallery) {
            return $gallery->toArray();
        }, $galleries);
        echo json_encode($galleries_arr);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['name']) && isset($data['user_id'])) {
        try {
            $gallery = createGallery($data['name'], $data['user_id']);
            if ($gallery) {
                echo $gallery->toJSON();
            } else {
                echo json_encode(['error' => 'Failed to create gallery']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id']) && isset($data['name'])) {
        try {
            $gallery = updateGallery($data['id'], $data['name']);
            if ($gallery) {
                echo $gallery->toJSON();
            } else {
                echo json_encode(['error' => 'Failed to update gallery']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id'])) {
        try {
            $success = deleteGallery($data['id']);
            if ($success) {
                echo json_encode(['message' => 'Gallery deleted successfully']);
            } else {
                echo json_encode(['error' => 'Failed to delete gallery']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Invalid request']);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Method not allowed']);
}
