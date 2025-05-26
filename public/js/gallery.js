$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const galleryId = urlParams.get('id');
    
    function redirectHome() {
        window.location.href = './';
    }
    
    if (!galleryId) {
        redirectHome();
        return;
    }

    function updateGalleryHeader(gallery) {
        $('.gallery-title').text(gallery.name);
        $('.author-name').text(gallery.author.name);
        document.title = `${gallery.name} - Galleros`;
    }

    function createPostElement(post) {
        return `
            <a href="./post.html?id=${post.id}" class="post-link">
                <div class="post-card">
                    <h2 class="post-title">${post.title}</h2>
                    <div class="post-stats">
                        <span class="likes">👍 ${post.likes}</span>
                        <span class="dislikes">👎 ${post.dislikes}</span>
                    </div>
                </div>
            </a>
        `;
    }

    function loadGallery() {
        $.ajax({
            url: `./api/gallery.php?id=${galleryId}`,
            method: 'GET',
            success: function(response) {
                if (!response || response.error) {
                    redirectHome();
                    return;
                }
                
                const gallery = new Gallery(response);
                updateGalleryHeader(gallery);
                
                // Load posts for this gallery
                loadPosts(galleryId);
            },
            error: function(xhr, status, error) {
                console.error('Error loading gallery:', error);
                redirectHome();
            }
        });
    }

    function loadPosts(galleryId) {
        $.ajax({
            url: `./api/post.php?gallery=${galleryId}`,
            method: 'GET',
            success: function(response) {
                if (!response || response.error) {
                    $('.posts-container').html('<p>No posts found.</p>');
                    return;
                }
                
                const postsContainer = $('.posts-container');
                postsContainer.empty();
                
                response.forEach(postData => {
                    postsContainer.append(createPostElement(postData));
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading posts:', error);
                $('.posts-container').html('<p>Error loading posts. Please try again later.</p>');
            }
        });
    }

    loadGallery();
});
