<?php 

require_once '_user.php';
require_once '_post.php';
require_once '_database.php';

class Comment {
    private int $id;
    private User $author;
    private int $likes;
    private int $dislikes;
    private Post $post;
    public function __construct(int $id = null) {
        // fetch the comment from the database
        global $db;
        $sql = "SELECT * FROM comments WHERE id = $id";
        $row = $db->query($sql)[0];
        if (!$row) return null;
        $this->id = $row['id'];
        $this->author = new User($row['author']);
        $this->likes = $row['likes'];
        $this->dislikes = $row['dislikes'];
        $this->post = new Post($row['post']);
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getAuthor(): User { return $this->author; }
    public function getLikes(): int { return $this->likes; }
    public function getDislikes(): int { return $this->dislikes; }
    public function getPost(): Post { return $this->post; }

    public function toJSON(): string {  
        return json_encode($this->toArray());
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'author' => $this->author->toArray(),
            'likes' => $this->likes,
            'dislikes' => $this->dislikes,
            'post'=> $this->post->toArray()
        ];
    }
}