<?php
require_once 'includes/config.php';
include 'templates/header.php';
include_once("../exercisefiles/includes/functions.php");
showMessage();
if ($login->state != 1) {
  if (isset($_POST["login"])) {
    $login->login($_POST["username"], $_POST["password"]);
  }
  ?>

  <div class="content">
    <div class="row">
      <div class="login-form">
        <form method="post" action="">
          <label for="username">Käyttäjätunnus</label>
          <input type="text" id="username" placeholder="Käyttäjätunnus" name="username">
          <label for="password">Salasana</label>
          <input type="password" id="password" placeholder="Salasana" name="password">
          <input type="submit" value="Kirjaudu" name="login">
        </form>
      </div>
    </div>
  </div>

  <?php
} else {
  header("location: admin.php");
}
include 'templates/footer.php';
?>
