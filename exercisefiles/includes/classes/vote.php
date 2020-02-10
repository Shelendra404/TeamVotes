<?php

class vote {

  public $conn;

  public function __construct($conn) {
    $this->conn = $conn;
  }

  public function sendVote($name, $phone, $email, $vote, $voteType, $adminEmail) {

    $confEmail = $adminEmail;
    $_SESSION['post_data'] = $_POST;

    // First check if the user has voted before
    $sql = "SELECT * FROM voters WHERE email = :email AND votetype = :voteType;";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(array(":email" => $email, ":voteType" => $voteType));
    $results = $stmt->rowCount();

    if ($results > 0) {
      // No more voting
      $_SESSION["message"] = "Olet jo äänestänyt tässä arvonnassa.";
      $_SESSION["message_type"] = 'error';
      header("location: index.php");
    }
    else {

      // If not, let them vote
      // Insert voter info into database
      $sql = "INSERT INTO voters (fullName, phoneNumber, email, voteType) VALUES (:name, :phone, :email, :voteType);";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute(array(":name" => $name, ":phone" => $phone, ":email" => $email, ":voteType" => $voteType));

      // Pull the auto-increment ID created in the voters table
      $last_id = $this->conn->lastInsertId();

      // and use it to create info in votes table
      $sql = "INSERT INTO votes (voteResult, voterID) VALUES (:vote, :id);";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute(array(":vote" => $vote, ":id" => $last_id));

      // Then send info to the given email about participation
      $msg = "Äänestäjän nimi: " . $name . " <br /> Puhelinnumero: " . $phone . " <br /> Sähköpostiosoite: " . $email . " <br /> Joukkue:  " .$vote;
      // Send email. This is intentionally commented, since development was done on localhost,
      // and there is no knowledge on how this code will be reviewed (SMTP?).
      // mail($confEmail,"Uusi äänestys!",$msg);
      header("location: submit.php");
    }
    
  }

  public function getVotes($teamName) {

    // Get the amount of all votes
    $sql = "SELECT * FROM votes;";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $totalVotes = $stmt->rowCount();

    $teams = [];
    $sql = "SELECT * FROM teams";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($teams as $team) {

      if (array_search($teamName, $team)) {

        $side = $team['teamID'];
        $sql = "SELECT * FROM votes WHERE voteResult = '$side';";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $amountVotes = $stmt->rowCount();
        if ($totalVotes == 0) {
          $result = 50;
        }
        else {

          $result = round(100*$amountVotes/$totalVotes, 0);
        }
        echo $result. '%';
      }
    }
  }

}
