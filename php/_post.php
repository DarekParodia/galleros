<?php 

require_once '_gallery.php';
require_once '_database.php';

class Post {
    private int $id;
    private string $title;
    private int $likes;
    private int $dislikes;
    private Gallery $gallery;

    public function __construct(int $id = null) {
        // fetch the post from the database
        global $db;
        $sql = "SELECT * FROM posts WHERE id = $id";
        $row = $db->query($sql)[0];
        if (!$row) return null;
        $this->id = $row['id'];
        $this->title = $row['title'];
        $this->likes = $row['likes'];
        $this->dislikes = $row['dislikes'];
        $this->gallery = new Gallery($row['gallery']);
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }
    public function getTitle(): string {
        return $this->title;
    }
    public function getLikes(): int {
        return $this->likes;
    }
    public function getDislikes(): int {
        return $this->dislikes;
    }
    public function getGallery(): Gallery {
        return $this->gallery;
    }
    public function toJSON(): string {  
        return json_encode($this->toArray());
    }
    public function toArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'gallery' => $this->gallery->toArray()
        ];
    }
}