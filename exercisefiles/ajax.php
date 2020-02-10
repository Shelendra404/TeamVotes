<?php
require_once 'includes/config.php';
include_once 'includes/classes/adminrights.php';

if (isset($_GET['q'])) {
  $data = $_GET['q'];
  $result = $adminrights->getWinners($data);
  if ($result) {
    echo $result;
  }
  else {
    echo "Ei mahdollisia voittajia jäljellä.";
  }
}
else if (isset($_GET['saveID'])) {
  $data = $_GET['saveID'];
  if (preg_match('/^\d+$/', $data)) {
    echo $adminrights->saveWinners($data);
  }

}
?>
