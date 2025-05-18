<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../php/partials/head.php" ?>
    <link rel="stylesheet" href="./css/tiles.css">
    <link rel="stylesheet" href="./css/gallery.css">
    <title>Galeros - Gallery</title>
</head>

<body>
    <?php include "../php/partials/header.php" ?>
    <div class="content-container">
        <section class="gallery-section">
            <div class="gallery-header">
                <h1 class="gallery-name">Gallery Name</h1>
                <p class="gallery-description">This is a description of the gallery. It provides details about the gallery's content and purpose.</p>
                <div class="author-info row">
                    <img src="https://picsum.photos/100" alt="Author Profile Picture" class="profile-picture">
                    <p class="author-name">Posted by: <strong>John Doe</strong></p>
                </div>
                <div class="gallery-stats">
                    <span class="likes">Likes: 123</span>
                    <span class="dislikes">Dislikes: 10</span>
                </div>
            </div>
            <div class="tiles-container">
                <?php
                // Example data for dynamic post tiles
                $posts = [
                    ["title" => "Post 1", "image" => "https://picsum.photos/600/400", "brief" => "This is a brief description of Post 1.", "likes" => 45, "dislikes" => 3],
                    ["title" => "Post 2", "image" => "https://picsum.photos/600/400", "brief" => "This is a brief description of Post 2.", "likes" => 30, "dislikes" => 5],
                    ["title" => "Post 3", "image" => "https://picsum.photos/600/400", "brief" => "This is a brief description of Post 3.", "likes" => 60, "dislikes" => 2],
                    ["title" => "Post 4", "image" => "https://picsum.photos/600/400", "brief" => "This is a brief description of Post 4.", "likes" => 25, "dislikes" => 8],
                ];

                if (!empty($posts)) {
                    foreach ($posts as $post) {
                        echo '<a href="./post.php" class="tile">'; // Wrap the entire tile in a clickable link
                        echo '<div class="tile-image">';
                        echo '<img src="' . htmlspecialchars($post["image"]) . '" alt="' . htmlspecialchars($post["title"]) . '">';
                        echo '</div>';
                        echo '<div class="tile-content">';
                        echo '<h2>' . htmlspecialchars($post["title"]) . '</h2>';
                        echo '<p class="tile-brief">' . htmlspecialchars($post["brief"]) . '</p>';
                        echo '<div class="tile-stats">';
                        echo '<span class="likes">Likes: ' . htmlspecialchars($post["likes"]) . '</span>';
                        echo '<span class="dislikes">Dislikes: ' . htmlspecialchars($post["dislikes"]) . '</span>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>'; // Close the link
                    }
                } else {
                    echo '<p class="no-posts">No posts available in this gallery. Please check back later.</p>';
                }
                ?>
            </div>
        </section>
    </div>
</body>

</html>