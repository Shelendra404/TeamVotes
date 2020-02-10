<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
//require_once 'includes/classes/vote.php';
include 'templates/header.php';
showMessage();

if (isset($_SESSION['post_data'])) {

  if (isset($_POST['share'])) {
    // First we validate that there is something on form
    $friendEmail = validateInputForm($_POST['friendEmail']);
    if (isset($_POST['friendEmail'])) {
      // Then we use function to make it safe

      if (isset($_SESSION['post_data']['email'])) {
        // Same to session post variables (just in case)
        $name = validateInputForm($_SESSION['post_data']['name']);
        $phone = validateInputForm($_SESSION['post_data']['phone']);
        $email = validateInputForm($_SESSION['post_data']['email']);
        $voted = validateInputForm($_SESSION['post_data']['vote']);
        $voteType = 2;

        // We allow sending multiple shares anyway, but only add a secondary vote once
        // No need to check here if has voted already, this is done on classes/vote.php
        $adminEmail = $adminrights->getEmail()[1];
        $vote->sendVote($name, $phone, $email, $voted, $voteType, $adminEmail);
      }

      sendEmail($email, $friendEmail);
      $_SESSION["message"] = "Viesti lähetetty kaverillesi ".$friendEmail."!";
      $_SESSION["message_type"] = 'success';
      header("location: submit.php");
      unset($_SESSION['post_data']['friendEmail']);
    }

  }
  ?>

  <div class="content">
    <div class="row bg-white">
      <div class="bg-yellow-grad">
        <div class="info-logo center-img ">
          <img src="images/logo.png" />
        </div>
        <div class="info-text text-share">
          <p class="tag-2">
            Kiitos osallistumisesta ja onnea arvontaan!
          </p>
          <p class="tag-3">
            Haluatko tuplata mahdollisuutesi voittoon? Kerro arvonnasta kavereillesi!
          </p>
          <p class="tag-4">
            Arvonnan jakaneet osallistuvat arvontaan kahdella arpalipulla.
          </p>
          <p>
            <button class="share" id="share" onclick="share(this)">Jaa</button>
            <form action="" method="post" id="share-to-friend">
              <input type="email" name="friendEmail" required="" placeholder="Kaverin sähköpostiosoite"/>
              <input type="submit" id="share" name="share" value="Lähetä viesti"/>
            </form>
          </p>
        </div>
      </div>

      <!-- Voting bar results -->
      <div class="row bg-white">
        <div class="teams">
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
    </div>
  </div>

</div>
<!-- Footer -->
<?php include 'templates/footer.php';
}
else {
  header("location: index.php");

}
