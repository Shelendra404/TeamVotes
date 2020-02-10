<?php require_once 'includes/config.php'; ?>

<!DOCTYPE html>
<html lang="fi" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="style.css">
    <script src="script.js"></script>
  <title>Äänestä ja voita!</title>
</head>

<body>

  <!-- Navigation -->
  <div class="navbar bg-black">
    <div class="navbar-left">
      <a href="index.php">
        <img src="images/logo.png" />
      </a>
    </div>
    <div class="navbar-right">
      <?php if ($login->state != 1) { ?>
        <a href="login.php">Kirjaudu sisään</a>
      <?php }
      else if ($login->state == 1) {
        if (isset($_SESSION["isAdmin"]) && $_SESSION['isAdmin']) {
          ?> <a class="nav-link" href="admin.php">Hallintapaneeli</a><a class="nav-link" href="logout.php">Kirjaudu ulos</a><?php
        }
        else {
          ?> <a class="nav-link" href="logout.php">Kirjaudu ulos</a> <?php
        }
      };
      ?>
    </div>
  </div>
