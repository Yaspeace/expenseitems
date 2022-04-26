<?php

require_once "PostgreDB.php";
require "db.cfg.php";

$accountId = $_POST['accountId'];
$rule_number = $_POST['rule_number'];

$db = new PostgreDB($defaultConnection);
$db->delete('rules', "uid = '$accountId' AND number = $rule_number");
$db->close();

header('Location: iframe.php');
 ?>
