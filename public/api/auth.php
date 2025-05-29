<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
require_once '../../php/_user.php';
require_once '../../php/_database.php';

session_start();

function authenticate(string $username, string $password) {
    global $db;
    // Use parameterized query to prevent SQL injection
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = $db->query($sql);
    
    if (empty($result)) {
        return null;
    }

    $user = $result[0];
    
    if (password_verify($password, $user['password'])) {
        return new User($user['id']);
    }
    
    return null;
}

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['name']) || !isset($data['password'])) {
        echo json_encode(['error' => 'Username and password are required']);
        exit;
    }

    $user = authenticate($data['name'], $data['password']);
    
    if ($user) {
        // Set session variables
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getName();
        $_SESSION['logged_in'] = true;

        // Set a cookie
        setcookie('user_id', $user->getId(), time() + (86400 * 30), "/"); // 30 days expiration
        setcookie('username', $user->getName(), time() + (86400 * 30), "/"); // 30 days expiration
        setcookie('logged_in', 'true', time() + (86400 * 30), "/"); // 30 days expiration
        
        echo json_encode([
            'success' => true,
            'user' => $user->toArray()
        ]);
    } else {
        echo json_encode(['error' => 'Invalid username or password']);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Handle logout
    session_destroy();
    echo json_encode(['success' => true]);
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Method not allowed']);
}
