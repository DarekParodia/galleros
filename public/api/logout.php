<?php

session_start();

// Clear session data
$_SESSION = array();

// Delete session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Clear login cookies
setcookie('user_id', '', time() - 3600, '/');
setcookie('username', '', time() - 3600, '/');
setcookie('logged_in', '', time() - 3600, '/');

// Destroy the session
session_destroy();

// Return JSON response
header('Content-Type: application/json');
echo json_encode(['success' => true]);
