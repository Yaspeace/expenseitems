<?php

require_once "lib.php";
require_once "PostgreDB.php";
require "db.cfg.php";

$period = $_POST['period'];
$accountId = $_POST['accountId'];

$db = new PostgreDB($defaultConnection);

$period_id = $db->first('periods', 'id', "name = '$period'")['id'];
$user = $db->first('user_periods', 'uid', "uid = '$accountId'");

if($user != null) {
  $values = ['period_id' => $period_id];
  $condition = ['uid' => $accountId];
  $db->update('user_periods', $values, $condition);
} else {
  $curdate = date("Y-m-d H:i:s");
  $data = ['uid' => $accountId, 'period_id' => $period_id];
  $db->insert('user_periods', $data);
}

$db->close();
header('Location: iframe.php');
 ?>
