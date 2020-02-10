<?php include 'templates/header.php'; ?>

<div class="content">
  <div class="logout">
    <div class="title">
      Odota hetki
    </div>
    <div class="notification">
      Kirjaudutaan ulos...
    </div>
    <div class="loader">
    </div>
  </div>
</div>
</body>

<?php
$_SESSION = [];
$loginstate = 0;
session_destroy();
header("Refresh:2; url=index.php");
?>
<!-- Footer -->
<?php include 'templates/footer.php'; ?>
