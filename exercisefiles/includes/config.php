<?php

session_start();

$db_servername = "*****";
$db_name = "*****";
$db_username = "*****";
$db_password = "*****";

try {
  $conn = new PDO("mysql:host=$db_servername;dbname=$db_name", $db_username, $db_password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $msg) {
  echo "Yhteyden ottaminen epäonnistui: " . $msg->getMessage();
}

// Requirements
require 'classes/login.php';
require 'classes/adminrights.php';
require 'classes/vote.php';

// Creating the necessary objects
$login = new login($conn);
$adminrights = new adminrights($conn);
$vote = new vote($conn);
