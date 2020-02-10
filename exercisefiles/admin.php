<?php
require_once 'includes/config.php';
include_once 'includes/classes/adminrights.php';
include_once 'templates/header.php';
include_once 'includes/functions.php';
showMessage();
?>

<?php
// Since this file isn't protected by .htaccess, there's an added security
// in form of session validation. Unwanted users will be also be redirected to front page.
// In addition, the form validation happens in adminrights class.
if(isset($_SESSION["isAdmin"])) {

  if(isset($_POST["addUser"])) {
    $adminrights = new adminrights($conn);
    $adminrights->validateuser();
  }
  if (isset($_POST["confirmAdminEmail"])) {
    $adminrights->setEmail($_POST['chooseEmail']);
    header("location: admin.php");
  }
  ?>

  <!-- Content -->
  <div class="content">
    <div class="block">

      <!-- Admin email -->
      <button class="accordion">Kilpailun äänestysten vastaanottava sähköposti</button>
      <div class="panel">
        <div class="panel-content">
          <ul>
            <li>
              Äänestykset lähetetään adminille: <strong> <?php echo $adminrights->getEmail()[0] . ' (' . $adminrights->getEmail()[1] . ').' ?></strong>
            </li>
            <li>
              Voit vaihtaa sähköpostiosoitetta valitsemalla alta adminin, jonka sähköpostiosoitetta käytetään.
            </li>
          </ul>
          <br />
        </div>
        <?php echo $adminrights->getAdmins(); ?>
      </div>

      <!-- Check for voters -->
      <button class="accordion">Tarkastele osallistujia</button>
      <div class="panel">
        <div class="panel-content">
          <?php echo $adminrights->getVoters(); ?>
        </div>

      </div>

      <!-- Get the winner -->
      <button id="winner" class="accordion">Arvo voittaja(t)</button>
      <div class="panel">
        <div class="panel-content">
          <div class="row bg-white">
            <div class="team-choice bg-grey" id="1" onclick="vote(this.id)">
              <div class="center-img">
                <img src="images/tappara_iso.png"/>
              </div>
              <div class="bg-black">
                TAPPARAN
              </div>
            </div>
            <div class="team-choice bg-grey" id="2" onclick="vote(this.id)">
              <div class="center-img">
                <img src="images/ilves_iso.png" </img>
              </div>
              <div class="bg-black">
                ILVEKSEN
              </div>
            </div>
          </div>
          <input id="show-winner" type="text" value="" readonly/>
          <input type="submit" class="share winner btn-winner" name="saveWinner" value="Tallenna" onclick="saveWinner()">
          <input type="submit" class="share winner btn-winner" name="pickWinner" value="Arvo voittaja" onclick="getWinner()">
        </div>
      </div>

      <!-- Show winners in a list -->
      <button class="accordion">Näytä voittajat</button>
      <div class="panel">
        <div class="panel-content">
          <?php echo $adminrights->showWinners(); ?>
        </div>
      </div>


      <!-- Add new user -->
      <button class="accordion">Lisää uusi käyttäjä</button>
      <div class="panel">
        <div class="panel-content">
          <div class="row">
            <div class="form-add-user">
              <form method="post" action="">
                <h1>Lisää käyttäjä</h1>
                <div>
                  <label for="username">Tunnus:</label>
                  <input type="text" placeholder="Valitse käyttäjätunnus" name="username" required="">
                </div>
                <div>
                  <label for="password">Salasana:</label>
                  <input type="password" placeholder="Valitse salasana" name="password" required="">
                </div>
                <div>
                  <label for="passwordCheck">Vahvista salasana:</label>
                  <input type="password" placeholder="Kirjoita salasana uudestaan" name="passwordCheck" required="">
                </div>
                <div>
                  <label for="email">Sähköpostiosoite:</label>
                  <input type="email" placeholder="Sähköposti" name="email" required="">
                </div>
                <div>
                  <label for="role">Oikeudet:</label><br />
                  <input type="radio" name="role" value="admin" required="">Admin
                  <input type="radio" name="role" value="user" required="">User
                </div>
                <input type="submit" value="Lähetä" name="addUser">
              </form>
              <hr>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
}
// Send all script kiddies back to front page
else {
  header("Refresh:0; url=index.php");
}
