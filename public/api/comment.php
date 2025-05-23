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
}