<?php
require_once 'config.php';
include_once 'templates/header.php';

function showMessage() {

   if (isset($_SESSION["message"])) {
      echo "<div class='message'><div class='site-message " . $_SESSION["message_type"] . "'>" . $_SESSION["message"] . "</div></div>";
      unset($_SESSION["message"]);
      unset($_SESSION["message_type"]);
   }
}

function validateInputForm($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sendEmail($email, $friendEmail) {

  $page = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
  $subject = "Kaverisi $email jakoi osallistumislinkin kilpailuumme! ";
  $message = "Käy tutustumassa kilpailuumme sivulla $page ja osallistu arvontaan! \r\n Oikein veikanneet osallistuvat arvontaan, jaossa mahtavia palkintoja!";
  $message = wordwrap($message, 70, "\r\n");


// // We would send it here but it's disabled, since there is no knowing in
// // what kind of environment this code will be reviewed.
// mail($friendEmail, $subject, $message);

}
?>
