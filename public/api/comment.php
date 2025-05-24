<?php

require_once '../../php/_user.php';
require_once '../../php/_post.php';
require_once '../../php/_database.php';
require_once '../../php/_comment.php';

function getComment(int $q)
{
    global $db;
    $sql = "SELECT * FROM comments WHERE id = $q";
    $row = $db->query($sql)[0];
    if (!$row) return null;
    $comment = new Comment($row['id']);
    return $comment;
}

function getAllComments()
{
    global $db;
    $comments = $db->getAll('comments');
    $comments = array_map(function ($comment) {
        return new Comment($comment['id']);
    }, $comments);
    return $comments;
}

function createComment(string $content, int $user_id, int $post_id)
{
    global $db;
    $sql = "INSERT INTO comments (content, author, post) VALUES ('$content', $user_id, $post_id)";
    $db->query($sql);
    if ($db->lastQuerrySuccessful()) {
        return new Comment($db->lastInsertId());
    }
    return null;
}

function updateComment(int $id, string $content)
{
    global $db;
    $sql = "UPDATE comments SET content = '$content' WHERE id = $id";
    $db->query($sql);
    if ($db->lastQuerrySuccessful()) {
        return new Comment($id);
    }
    return null;
}

function deleteComment(int $id)
{
    global $db;
    $sql = "DELETE FROM comments WHERE id = $id";
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
        $comment = getComment($_GET['id']);
        if ($comment) {
            echo $comment->toJSON();
        } else {
            echo json_encode(['error' => 'Comment not found']);
        }
    } else {
        $comments = getAllComments();
        $comments_arr = array_map(function ($comment) {
            return $comment->toArray();
        }, $comments);
        echo json_encode($comments_arr);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['content']) && isset($data['user_id']) && isset($data['post_id'])) {
        try {
            $comment = createComment($data['content'], $data['user_id'], $data['post_id']);
            if ($comment) {
                echo $comment->toJSON();
            } else {
                echo json_encode(['error' => 'Failed to create comment']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Invalid request', 'message' => 'Content, user_id, and post_id are required', 'data' => $data]);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['id']) && isset($data['content'])) {
        try {
            $comment = updateComment($data['id'], $data['content']);
            if ($comment) {
                echo $comment->toJSON();
            } else {
                echo json_encode(['error' => 'Failed to update comment']);
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
            $success = deleteComment($data['id']);
            if ($success) {
                echo json_encode(['message' => 'Comment deleted successfully']);
            } else {
                echo json_encode(['error' => 'Failed to delete comment']);
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