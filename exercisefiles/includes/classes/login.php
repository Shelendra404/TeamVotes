<?php

class login {

  public $conn;
  public $state;

  public function __construct($conn) {
    $this->conn = $conn;

    if (isset($_SESSION["isAdmin"])) {
      $this->state = 1;
    }
    else {
      $this->state = 0;
    }
  }

  // The login process
  public function login($username, $password) {

    $sql = "SELECT username, role, password FROM users WHERE username = :username";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(array(":username" => $username));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $hash = ($result["password"]);

    if (password_verify($password, $hash)) {
      if ($stmt->rowCount() == 1) {
        $this->state = 1;
        // Allows (or doesn't allow) unique name used as admin value on session identifier
        if ($result["role"] == "admin" || $result["role"] == "superadmin") {
          $_SESSION["isAdmin"] = true;
        } else {
          $_SESSION["isAdmin"] = false;
        }
        $_SESSION["username"] = $result["username"];
        header("location: index.php");
      }
    }
    else {
      $_SESSION["message"] = "Tunnus ja/tai salasana ei ole oikein.";
      $_SESSION["message_type"] = 'error';
      return false;
    }
  }

}
