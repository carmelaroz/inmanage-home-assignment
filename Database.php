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

    public function select($table, $columns = ['*'], $whereClause = null, $params = [])
    {
        $columnsString = implode(", ", $columns);
        $query = "SELECT $columnsString FROM $table";
        
        if ($whereClause) {
            $query .= " WHERE $whereClause";
        }

        $stmt = $this->conn->prepare($query);
        
        // Bind parameters if provided
        if (!empty($params)) {
            $stmt = $this->bindParameters($stmt, $params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        return $data;
    }

    public function insert($table, $data)
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindParameters($stmt, array_values($data));

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function update($table, $data, $whereClause, $params = [])
    {
        $setClause = implode(", ", array_map(fn($col) => "$col = ?", array_keys($data)));
        $query = "UPDATE $table SET $setClause WHERE $whereClause";

        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindParameters($stmt, array_merge(array_values($data), $params));

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    public function delete($table, $whereClause, $params = [])
    {
        $query = "DELETE FROM $table WHERE $whereClause";

        $stmt = $this->conn->prepare($query);
        $stmt = $this->bindParameters($stmt, $params);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    private function bindParameters($stmt, $params)
    {
        if ($params) {
            $types = str_repeat("s", count($params)); // Assume all parameters are strings
            $stmt->bind_param($types, ...$params);
        }
        return $stmt;
    }

}
?>