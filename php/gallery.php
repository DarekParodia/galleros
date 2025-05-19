<?php 

require_once 'user.php';
require_once 'database.php';

class Gallery {
    private int $id;
    private string $name;
    private User $author;
    public function __construct(int $id = null) {
        // fetch the gallery from the database
        global $db;
        $sql = "SELECT * FROM galleries WHERE id = $id";
        $row = $db->query($sql)[0];
        if (!$row) return null;
        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->author = new User($row['author']);
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getAuthor(): User {
        return $this->author;
    }
    public function getPosts(): array {
        global $db;
        $sql = "SELECT * FROM posts WHERE gallery = $this->id";
        $posts = $db->query($sql);
        return array_map(function($post) {
            return new Post($post['id']);
        }, $posts);
    }

    public function toJSON(): string {  
        return json_encode([
            'id' => $this->id,
            'name' => $this->name,
            'author' => $this->author->toJSON()
        ]);
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'author' => $this->author->toArray()
        ];
    }
}