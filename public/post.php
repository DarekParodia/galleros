<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "../php/head.php" ?>
    <link rel="stylesheet" href="./css/post.css">
    <title>Galeros - Post</title>
</head>

<body>
    <?php include "../php/header.php" ?>
    <div class="content-container">
        <button onclick="history.back()" class="go-back-button">Go Back</button> <!-- Go Back Button -->
        <section class="post-section">
            <h1 class="post-title">Post Title</h1>
            <div class="post-author">
                <img src="https://picsum.photos/50" alt="Author Profile Picture" class="profile-picture">
                <p class="author-name">Posted by: <strong>John Doe</strong></p>
            </div>
            <div class="post-image">
                <img src="https://picsum.photos/1200/600" alt="Post Image">
            </div>
            <div class="post-content">
                <p>This is the content of the post. It can include text, images, or any other information related to the post. The content is displayed across the full width of the site for better readability and focus.</p>
            </div>
            <div class="post-stats">
                <span class="likes">Likes: 123</span>
                <span class="dislikes">Dislikes: 10</span>
            </div>
        </section>
    </div>
</body>

</html>