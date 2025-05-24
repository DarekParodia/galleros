<?php

require_once "../../php/_post.php";
require_once "../../php/_gallery.php";
require_once "../../php/_comment.php";
require_once "../../php/_database.php";

function getPost(int $q)
{
    global $db;
    $sql = "SELECT * FROM posts WHERE id = $q";
    $row = $db->query($sql)[0];
    if (!$row) return null;
    $post = new Post($row['id']);
    return $post;
}

function getAllPosts()
{
    global $db;
    $posts = $db->getAll('posts');
    $posts = array_map(function ($post) {
        return new Post($post['id']);
    }, $posts);
    return $posts;
}

function createPost(string $title, int $gallery_id)
{
    global $db;
    $sql = "INSERT INTO posts (title, gallery) VALUES ('$title', $gallery_id)";
    $db->query($sql);
    if ($db->lastQuerrySuccessful()) {
        return new Post($db->lastInsertId());
    }
    return null;
}

function updatePost(int $id, string $title)
{
    global $db;
    $sql = "UPDATE posts SET title = '$title' WHERE id = $id";
    $db->query($sql);
    if ($db->lastQuerrySuccessful()) {
        return new Post($id);
    }
    return null;
}

function deletePost(int $id)
{
    global $db;
    $sql = "DELETE FROM posts WHERE id = $id";
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
        $post = getPost($_GET['id']);
        if ($post) {
            echo $post->toJSON();
        } else {
            echo json_encode(['error' => 'Post not found']);
        }
    } else {
        $posts = getAllPosts();
        $posts_arr = array_map(function ($post) {
            return $post->toArray();
        }, $posts);
        echo json_encode($posts_arr);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['title']) && isset($data['gallery_id'])) {
        try {
            $post = createPost($data['title'], $data['gallery_id']);
            if ($post) {
                echo $post->toJSON();
            } else {
                echo json_encode(['error' => 'Failed to create post']);
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
    if (isset($data['id']) && isset($data['title']) && isset($data['content'])) {
        try {
            $post = updatePost($data['id'], $data['title']);
            if ($post) {
                echo $post->toJSON();
            } else {
                echo json_encode(['error' => 'Failed to update post']);
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
            $success = deletePost($data['id']);
            if ($success) {
                echo json_encode(['message' => 'Post deleted successfully']);
            } else {
                echo json_encode(['error' => 'Failed to delete post']);
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