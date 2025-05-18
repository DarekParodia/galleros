<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../php/partials/head.php" ?>
    <link rel="stylesheet" href="./css/tiles.css">
    <title>Galeros</title>
</head>

<body>
    <?php include "../php/partials/header.php" ?>
    <div class="content-container">
        <section class="gallery-section">
            <h1 class="section-header">Galerie Użytkowników</h1>
            <div class="tiles-container">
                <?php
                // Example data for dynamic tiles
                $galleries = [
                    ["title" => "Gallery 1", "description" => "Description for Gallery 1", "image" => "https://picsum.photos/600/400"],
                    ["title" => "Gallery 2", "description" => "Description for Gallery 2", "image" => "https://picsum.photos/600/400"],
                    ["title" => "Gallery 3", "description" => "Description for Gallery 3", "image" => "https://picsum.photos/600/400"],
                    ["title" => "Gallery 4", "description" => "Description for Gallery 4", "image" => "https://picsum.photos/600/400"],
                ];

                if (!empty($galleries)) {
                    foreach ($galleries as $gallery) {
                        echo '<div class="tile">';
                        echo '<div class="tile-image">';
                        echo '<img src="' . htmlspecialchars($gallery["image"]) . '" alt="' . htmlspecialchars($gallery["title"]) . '">';
                        echo '</div>';
                        echo '<div class="tile-content">';
                        echo '<h2>' . htmlspecialchars($gallery["title"]) . '</h2>';
                        echo '<p>' . htmlspecialchars($gallery["description"]) . '</p>';
                        echo '<a href="./gallery.php" class="view-button">View Gallery</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="no-galleries">No galleries available at the moment. Please check back later.</p>';
                }
                ?>
            </div>
        </section>
    </div>
</body>

</html>