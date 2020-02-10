<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/classes/vote.php';
include_once 'templates/header.php';

showMessage();
if (isset($_POST["sendVote"])) {
  // First validate inputs before sending it to be processed
  $name = validateInputForm($_POST['name']);
  $phone = validateInputForm($_POST['phone']);
  $email = validateInputForm($_POST['email']);
  $voted = validateInputForm($_POST['vote']);
  $voteType = 1;

  $adminEmail = $adminrights->getEmail()[1];
  $vote->sendVote($name, $phone, $email, $voted, $voteType, $adminEmail);
}
?>

<!-- Content area -->
<div class="content">
  <!-- Teams -->
  <div class="teams">
    <div class="row bg-black center tag-1">
      Kumman joukoissa seisot?
    </div>
    <div class="row bg-white center tag-2">
      Äänestä ja osallistu arvontaan!
    </div>
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

    <!-- Voting bar results -->
    <div class="row bg-white">
      <div class = "results">
        <img align="left" src="images/tappara_pieni.png" />
        <div class="result-bar team-1 results-bar-blue-grad" style="width: <?php echo $vote->getVotes('tappara'); ?>;"><?php echo $vote->getVotes('tappara'); ?>
        </div>
        <div class="result-bar team-2 results-bar-yellow-grad" style="width:<?php echo $vote->getVotes('ilves'); ?>;"><?php echo $vote->getVotes('ilves'); ?>
        </div>
        <img align="right" src="images/ilves_pieni.png" />
      </div>
    </div>
  </div>

  <!-- Info and voting form area -->
  <div class="row bg-white voting">
    <div class="info bg-yellow-grad">
      <div class="info-text info-left ">
        <p class="text-lead">
          Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum
          Lorem ipsum Lorem ipsum
        </p>
        <p>
          Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum Lorem ipsum
          Lorem ipsum Lorem ipsum
        </p>
      </div>
      <div class="info-right center-img">
        <img src="images/logo.png" />
      </div>
    </div>
    <div class="form">
      <form action="" method="post">
        <form-title>Täytä tietosi:</form-title><br />
        <div>
          <label>Nimi: *</label>
          <input type="text" name="name" required>
          <label>Puhelin: *</label>
          <input type="text" name="phone" required/>
          <label>Sähköpostiosoite: *</label>
          <input type="email" name="email" required/>
          <label>
            Uskon joukkueeseen *:
          </label><br />
          <input type="radio" name="vote" value="1" id="team-1" required="" onclick="change(this)">Tappara
          <input type="radio" name="vote" value="2" id="team-2" required="" onclick="change(this)">Ilves
        </div>
        <br /><label>* = pakolliset tiedot</label>
        <input type="submit" value="LÄHETÄ" name="sendVote">
      </form>
    </div>
  </div>
</div>

<!-- Footer -->
<?php include 'templates/footer.php'; ?>
