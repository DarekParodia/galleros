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
}