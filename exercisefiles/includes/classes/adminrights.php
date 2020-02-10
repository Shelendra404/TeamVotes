<?php

class adminrights {
  public $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }

  // Checking if username is already in use before registering users
  public function checkUsername($username) {

    $sql = "SELECT * FROM users WHERE username = '$username';";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(array(":username" => $username));
    $results = $stmt->rowCount();
    return $results;

  }

  // Validating user info
  public function validateUser() {

    $username = validateInputForm($_POST['username']);
    // Make it safe
    $password = validateInputForm($_POST["password"]);
    $passwordCheck = validateInputForm($_POST["passwordCheck"]);
    $email = validateInputForm($_POST["email"]);
    $role= validateInputForm($_POST["role"]);
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // First we check that the given passwords match each pspell_config_runtogether
    if ($password == $passwordCheck) {
      // check that the same username isn't already in use...
      if ($this->checkUsername($username) != 0) {
        $_SESSION["message"] = "Tämä tunnus on jo käytössä, valitse toinen.";
        $_SESSION["message_type"] = 'error';
          header("location: admin.php");
      }
      // if not, add a new user
      else {
        $this->addUser($username, $hash, $role, $email);
        $_SESSION["message"] = "Käyttäjä lisätty onnistuneesti!";
        $_SESSION["message_type"] = 'success';
        header("location: admin.php");
      }
    }
    // If the passwords don't match, break out immediately.
    else {
      $_SESSION["message"] = "Salasanan vahvistuksen täytyy olla sama kuin salasana.";
      $_SESSION["message_type"] = 'error';
        header("location: admin.php");
    }

  }

  // Adding new users
  public function addUser($username, $password, $role, $email) {

    if (isset($_SESSION["isAdmin"])) {
      $sql = "INSERT INTO users (username, password, role, email) VALUES (:username, :password, :role, :email);";
      $stmt = $this->conn->prepare($sql);
      return $stmt->execute(array(":username" => $username, ":password"=> $password, ":role"=> $role, ":email"=>$email));
    }

  }

  // Listing all admins in adminpanel
  public function getAdmins() {

    // Get all registered users with administratory roles to be shown as a list on admin panel
    $sql = "SELECT * FROM users WHERE role = 'admin' OR role = 'superadmin';";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo '<form action="" method="post" class="flex-table">';
    foreach ($users as $admin) {
      echo '<label for="'.$admin['username'].'" class="vote-email">';
      echo '<div class="flex-row">';
      echo '<div class="flex-cell first-cell"> '.$admin['username'].'</div>';
      echo '<div class="flex-cell"> '.$admin['email'].' </div>';
      echo '<div><input type="radio" name="chooseEmail" id="'.$admin['username'].'" value="'.$admin['username'].'" required ></div>';
      echo '</div>';
      echo '</label>';
    }
    echo '<input type="submit" value="Vahvista" name="confirmAdminEmail"/>';
    echo '</form>';

  }

  // Getting the email of the admin who gets notifications from voting
  public function getEmail() {
    // Check who is the super admin in users
    $sql = "SELECT * FROM users WHERE role = 'superadmin';";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // This shouldn't happen, but failsafe.
    if (count($users) == 0) {
      echo "Ei asetettua sähköpostiosoitetta.";
    }
    else {
      $superadminInfo = [$users[0]['username'], $users[0]['email']];
      return $superadminInfo;
    }
  }

  // Changing the email of the admin who gets notifications from voting
  public function setEmail($user) {

    // Update role values to users
    $sql = "UPDATE users SET role =  (case when role = 'superadmin' then 'admin'
      when role = 'admin' AND username = :user then 'superadmin'
      when role  = 'admin' AND username <> :user then 'admin' end)
      WHERE role in ('superadmin', 'admin')";

      $stmt = $this->conn->prepare($sql);
      $stmt->execute(array(':user' => $user));

      $_SESSION["message"] = "Sähköposti vaihdettu!";
      $_SESSION["message_type"] = 'success';
      header("location: admin.php");
    }

  // Showing voters in adminpanel
  public function getVoters() {

    $sql = "SELECT voters.*, votes.voteResult
    FROM voters
    INNER JOIN votes
    ON voters.voterID = votes.voterID";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $voters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $i = 1;

    echo '<div class="flex-table full-width">';
    foreach ($voters as $voter) {
      echo '<div>';
      echo $i;
      echo '</div>';
      echo '<div class="voter">';

      foreach ($voter as $key => $value) {
        echo '<div class="flex-row voters">';
        echo '<div class="flex-cell">' .$key. '</div><div class="flex-cell">' .$value. '</div>';
        echo '</div>';
      }
      echo '</div>';
      $i++;
    }
    echo '</div>';

  }

  // Showing winners in adminpanel
  public function showWinners() {

    $sql = "SELECT voters.*, winners.voterID
    FROM voters
    INNER JOIN winners
    ON voters.voterID = winners.voterID";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $voters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $i = 1;

    echo '<div class="flex-table full-width">';
    foreach ($voters as $voter) {
      echo '<div>';
      echo $i;
      echo '</div>';
      echo '<div class="voter">';

      foreach ($voter as $key => $value) {
        echo '<div class="flex-row winners">';
        echo '<div class="flex-cell">' .$key. '</div><div class="flex-cell">' .$value. '</div>';
        echo '</div>';
      }
      echo '</div>';
      $i++;
    }
    echo '</div>';
  }

  // Choosing a random winner
  public function getWinners($data) {

    $winningTeam = $data;

    // From the chosen winning team, randomly get one voter that is NOT yet in the winners table
    $sql = "SELECT * FROM votes
    WHERE voteresult = :winningTeam
    AND voterID NOT IN (SELECT voterID FROM winners)
    ORDER BY RAND()
    LIMIT 1;
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute(array(':winningTeam' => $winningTeam));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($result) > 0) {
      $winner = $result[0]['voterID'];
      return $winner;
    }

  }

  // Saving winners to database
  public function saveWinners($data) {

    $winnerID = $data;

    $sql = "INSERT INTO winners (voterID) VALUES (:winnerID);";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(array(":winnerID" => $winnerID));

    $sql = "SELECT fullName, email FROM voters WHERE voterID = :winnerID;";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(array(":winnerID" => $winnerID));
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $winner =  'Voittaja on ' .$result[0]['fullName']. ' (' .$result[0]['email'] .')! Voittajan tiedot tallennettu tietokantaan.';
    echo $winner;
  }

}
