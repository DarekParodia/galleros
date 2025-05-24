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

function createUser(string $name, string $password)
{
    global $db;
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password) VALUES ('$name', '$password_hash')";
    $db->query($sql);
    if ($db->lastQuerrySuccessful()) {
        return new User($db->lastInsertId());
    } else {
        return null;
    }
}

function updateUser(int $id, string $name, string $password)
{
    global $db;
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET username = '$name', password = '$password_hash' WHERE id = $id";
    $db->query($sql);
    if ($db->lastQuerrySuccessful()) {
        return new User($id);
    } else {
        return null;
    }
}

function deleteUser(int $id)
{
    global $db;
    $sql = "DELETE FROM users WHERE id = $id";
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
} else

    // POST

    // create user
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['name']) && isset($data['password'])) {
            try {
                $user = createUser($data['name'], $data['password']);
                if ($user) {
                    echo $user->toJSON();
                } else {
                    echo json_encode(['error' => 'Failed to create user']);
                }
            } catch (Exception $e) {
                echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'Invalid request']);
        }
    } else

        // PATCH

        // update user
        if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
            header('Content-Type: application/json');
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id']) && isset($data['name']) && isset($data['password'])) {
                try {
                    $user = updateUser($data['id'], $data['name'], $data['password']);
                    if ($user) {
                        echo $user->toJSON();
                    } else {
                        echo json_encode(['error' => 'Failed to update user']);
                    }
                } catch (Exception $e) {
                    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
                }
            } else {
                echo json_encode(['error' => 'Invalid request']);
            }
        } else

            // DELETE
            if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
                header('Content-Type: application/json');
                $data = json_decode(file_get_contents('php://input'), true);
                if (isset($data['id'])) {
                    try {
                        $success = deleteUser($data['id']);
                        if ($success) {
                            echo json_encode(['message' => 'User deleted successfully']);
                        } else {
                            echo json_encode(['error' => 'Failed to delete user']);
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
