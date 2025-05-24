<?php 

class Database {
    private $con;

    public function __construct() {
        $this->con = new mysqli('192.168.1.207', 'darekparodia', '', 'galleros', 3306);

        if ($this->con->connect_error) {
            // die("Connection failed: " . $this->con->connect_error);
            $this->con = new mysqli('localhost', 'darekparodia', '', 'galleros', 3306);
            if ($this->con->connect_error) {
                die("Connection failed: " . $this->con->connect_error);
            }
        } else {
            // echo "Connected successfully";
        }
    }

    public function getConnection() {
        return $this->con;
    }

    public function closeConnection() {
        $this->con->close();
    }

    public function getAll(string $table) {
        $sql = "SELECT * FROM $table";
        $result = $this->con->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function query(string $sql) {
        $result = $this->con->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public function lastInsertId() {
        return $this->con->insert_id;
    }

    public function lastQuerrySuccessful() {
        return $this->con->affected_rows > 0;
    }
}

// Create a global object for the Database class
$db = new Database();