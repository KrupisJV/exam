<?php
class CommentsHandler {
    private $servername = "localhost";
    private $username = "root";
    private $password = "root";
    private $dbname = "eksamens";
    private $conn;

    public function __construct() {
        // Create connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function addComment($name, $email, $message) {
        $name = $this->conn->real_escape_string($name);
        $email = $this->conn->real_escape_string($email);
        $message = $this->conn->real_escape_string($message);

        $sql = "INSERT INTO comments (name, email, message) VALUES ('$name', '$email', '$message')";

        if ($this->conn->query($sql) === TRUE) {
            return ["success" => true, "message" => "Comment saved successfully"];
        } else {
            return ["success" => false, "message" => "Error: " . $sql . "<br>" . $this->conn->error];
        }
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

// Usage example:
$handler = new CommentsHandler();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $result = $handler->addComment($name, $email, $message);
    echo json_encode($result);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}

$handler->closeConnection();
?>
