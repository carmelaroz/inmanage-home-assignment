<?php
class Database {
    public $conn;

    // Constructor to initialize the connection
    public function __construct($host, $username, $password, $dbname) {
        $this->conn = new mysqli($host, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function close() {
        $this->conn->close();
    }

    public function select($query) {
        $result = $this->conn->query($query);
        if (!$result) {
            throw new Exception("Query failed: " . $this->conn->error);
        }
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function insert($query) {
        if (!$this->conn->query($query)) {
            throw new Exception("Insert failed: " . $this->conn->error);
        }
        // Return the ID of the inserted record (if applicable)
        return $this->conn->insert_id;
    }
    
    public function delete($query) {
        if (!$this->conn->query($query)) {
            throw new Exception("Delete failed: " . $this->conn->error);
        }
        // Return the number of affected rows
        return $this->conn->affected_rows;
    }
    
    public function update($query) {
        if (!$this->conn->query($query)) {
            throw new Exception("Update failed: " . $this->conn->error);
        }
        // Return the number of affected rows
        return $this->conn->affected_rows;
    }
}
?>