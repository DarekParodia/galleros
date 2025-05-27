<?php 

require_once '_database.php'; // Assuming db.php contains the database connection logic

class User {
    private int $id;
    private string $name;
    
    public function __construct(int $id = null) {
        // fetch the user from the database
        global $db;
        $sql = "SELECT * FROM users WHERE id = $id";
        $row = $db->query($sql)[0];
        if (!$row) return null;
        $this->id = $row['id'];
        $this->name = $row['username'];
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }
    public function getName(): string {
        return $this->name;
    }
    public function toJSON(): string {  
        return json_encode($this->toArray());
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}