<?php

function FindUniqNum($nums) {
  if($nums == null)
    return 1;
  $i = 1;
  while(in_array($i, $nums)) {
    $i++;
  }
  return $i;
}

require_once 'lib.php';
require_once 'PostgreDb.php';
require "db.cfg.php";

$accountId = $_POST['accountId'];
$counterparty = $_POST['counterparty'];
$operand1 = $_POST['operand1'];
$project = $_POST['project'];
$operand2 = $_POST['operand2'];
$comment = $_POST['comment'];
$operand3 = $_POST['operand3'];
$purpose = $_POST['purpose'];
$expenseitem = $_POST['expenseitem'];

$db = new PostgreDB($defaultConnection);

try {
  $user_rules_nums = $db->column('rules', 'number', "uid = '$accountId'");
  $user_rules_num = FindUniqNum($user_rules_nums);

  $insert_data = [
    'id' => null,
    'counterparty_id' => $counterparty,
    'operand1' => $operand1,
    'project_id' => $project,
    'operand2' => $operand2,
    'comment' => $comment,
    'operand3' => $operand3,
    'purpose' => $purpose,
    'expenseitem_id' => $expenseitem,
    'number' => $user_rules_num,
    'uid' => $accountId
  ];
  $db->insert('rules', $insert_data);
}
catch(Throwable $e) {
  $errorMessage = 'Ошибка при добавлении нового правила!';
}

$db->close();
$successMessage = 'Успешно добавлено!';
header("Location: iframe.php");
?>
