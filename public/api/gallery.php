<?php

require_once '../../php/user.php';
require_once '../../php/post.php';
require_once '../../php/database.php';
require_once '../../php/gallery.php';

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

// ==========
// API
// ==========

// GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
// if (true) {
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
}
