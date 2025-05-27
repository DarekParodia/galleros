<?php
require_once '../../php/_database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('error_log', '/tmp/php-errors.log');

session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Configure upload settings
$uploadDir = '../img/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

function handleUpload() {
    global $db, $uploadDir;
    
    // Add debug logging
    error_log("Starting file upload process");
    
    // Validate file upload
    if (!isset($_FILES['thumbnail']) || !isset($_POST['title'])) {
        return ['error' => 'Missing required fields'];
    }

    $file = $_FILES['thumbnail'];
    $title = $_POST['title'];
    $type = $_POST['type'];

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        return ['error' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed.'];
    }

    // Generate unique filename (remove spaces from filename)
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = str_replace(' ', '', uniqid() . '.' . $extension);
    $userId = $_SESSION['user_id']; // Use actual user ID from session

    // Set upload directory based on type
    switch ($type) {
        case 'gallery':
            $uploadDir = __DIR__ . '/../img/galleries/';
            $relativeDir = 'img/galleries/';
            break;
        case 'post':
            $uploadDir = __DIR__ . '/../img/posts/';
            $relativeDir = 'img/posts/';
            break;
        default:
            error_log("Invalid type specified: " . $type);
            return ['error' => 'Invalid type specified'];
    }

    // Create user directory if it doesn't exist
    $userDir = $uploadDir . $userId;
    if (!file_exists($userDir)) {
        if (!mkdir($userDir, 0777, true)) {
            error_log("Failed to create user directory: " . $userDir);
            return ['error' => 'Failed to create upload directory'];
        }
        chmod($userDir, 0777); // Ensure directory is writable
    }

    

    $targetPath = $userDir . '/' . $filename;

    if ($type === 'gallery') {
        $sql = "INSERT INTO galleries (name, author) VALUES ('$title', $userId)";
        $db->query($sql);
        
        // get id of new gallery
        $galleryId = $db->lastInsertId();
        
        // Create gallery directory
        $galleryDir = $uploadDir . $galleryId;
        if (!file_exists($galleryDir)) {
            if (!mkdir($galleryDir, 0777, true)) {
                error_log("Failed to create gallery directory: " . $galleryDir);
                return ['error' => 'Failed to create gallery directory'];
            }
            chmod($galleryDir, 0777);
        }
        
        $targetPath = $galleryDir . "/thumbnail.jpg";
        $relativePath = $relativeDir . $galleryId . "/thumbnail.jpg";
    } else if ($type === 'post') {
        $gallery_id = isset($_POST['gallery_id']) ? (int)$_POST['gallery_id'] : null;
        $sql = "INSERT INTO posts (title, gallery) VALUES ('$title', $gallery_id)";
        $db->query($sql);
        
        // get id of new post
        $postId = $db->lastInsertId();
        
        
        // Create post directory
        $postDir = $uploadDir . $postId;
        if (!file_exists($postDir)) {
            if (!mkdir($postDir, 0777, true)) {
                error_log("Failed to create post directory: " . $postDir);
                return ['error' => 'Failed to create post directory'];
            }
            chmod($postDir, 0777);
        }
        
        $targetPath = $postDir . "/thumbnail.jpg";
        $relativePath = $relativeDir . $postId . "/thumbnail.jpg";
    } else {
        error_log("Invalid type specified: " . $type);
        return ['error' => 'Invalid type specified'];
    }

    // Check directory permissions for the target directory
    $targetDir = dirname($targetPath);
    if (!is_writable($targetDir)) {
        error_log("Directory not writable: " . $targetDir);
        return ['error' => 'Upload directory is not writable'];
    }
    
    error_log("Attempting to upload to: " . $targetPath);

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        error_log("Failed to move uploaded file. Upload error code: " . $_FILES['thumbnail']['error']);
        error_log("From: " . $file['tmp_name'] . " To: " . $targetPath);
        return ['error' => 'Failed to upload file. Check permissions.'];
    }

    // Save to database with correct relative path
    $relativePath = $relativeDir . $filename;

    // Convert to jpg
    // if ($type === 'gallery') {
    //     $image = imagecreatefromstring(file_get_contents($targetPath));
    //     if ($image === false) {
    //         unlink($targetPath);
    //         return ['error' => 'Failed to create image from uploaded file'];
    //     }
    //     $jpgPath = $uploadDir . $userId . '/thumbnail.jpg';
    //     imagejpeg($image, $jpgPath, 90);
    //     imagedestroy($image);
    //     $relativePath = $relativeDir . 'thumbnail.jpg';
    // }
    

    return [
        'success' => true,
        'id' => $db->lastInsertId(),
        'title' => $title,
        'thumbnail_path' => $relativePath
    ];
}

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $result = handleUpload();
    echo json_encode($result);
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Method not allowed']);
}
