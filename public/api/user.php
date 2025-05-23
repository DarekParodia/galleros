<?php

require_once '../../php/_user.php';
require_once '../../php/_database.php';

function getUser(int $q)
{
    global $db;
    $sql = "SELECT * FROM users WHERE id = $q";
    $row = $db->query($sql)[0];
    if (!$row) return null;
    $user = new User($row['id']);
    return $user;
}

function getAllUsers()
{
    global $db;
    $users = $db->getAll('users');
    $users = array_map(function ($user) {
        return new User($user['id']);
    }, $users);
    return $users;
}

// ==========
// API
// ==========

// GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Content-Type: application/json');
    if (isset($_GET['id'])) {
        $user = getUser($_GET['id']);
        if ($user) {
            echo $user->toJSON();
        } else {
            echo json_encode(['error' => 'User not found']);
        }
    } else {
        $users = getAllUsers();
        $users_arr = array_map(function ($user) {
            return $user->toArray();
        }, $users);
        echo json_encode($users_arr);
    }
}