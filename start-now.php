<?php

require_once "lib.php";
require_once "PostgreDB.php";
require "db.cfg.php";

try {
  $accountId = $_POST['accountId'];
  $flashStart = $_POST['flashStart'];

  $jsonApi = new JsonApi('ae0ecb126b9947cdb7c30c55b940a1201c3c4dd6');

  $db = new PostgreDB($defaultConnection);

  $rules = [];

  if($flashStart == 'true') {
    $operand1 = $_POST['operand1'];
    $operand2 = $_POST['operand2'];
    $operand3 = $_POST['operand3'];
    $counterparty = $_POST['counterparty'];
    $project = $_POST['project'];
    $comment = $_POST['comment'];
    $purpose = $_POST['purpose'];
    $_expenseitem = $_POST['expenseitem'];

    $rule = [
      'id' => null,
      'counterparty_id' => $counterparty,
      'operand1' => $operand1,
      'project_id' => $project,
      'operand2' => $operand2,
      'comment' => $comment,
      'operand3' => $operand3,
      'purpose' => $purpose,
      'expenseitem_id' => $_expenseitem,
      'number' =>  null,
      'uid' => $accountId
    ];

    $rules[] = $rule;
  } else {
    $rules = $db->select('rules', 'id', "uid = '$accountId'");
  }

  $paymentouts = $jsonApi->getObjects("paymentout", true);
  $cashouts = $jsonApi->getObjects("cashout", true);
  $payments = array_merge($paymentouts, $cashouts);
  foreach($payments as $p) {
    $p_type = $p->meta->type;
    $p_agent_id = $jsonApi->getByURL($p->agent->meta->href)->id;
    $p_project_id = $jsonApi->getByURL($p->project->meta->href)->id;
    $p_description = property_exists($p, 'description') ? $p->description : "";
    $p_purpose = property_exists($p, 'paymentPurpose') ? $p->paymentPurpose : "";
    foreach($rules as $r) {
      $suits = true;
      switch ($r['operand1']) {
        case 'И':
          $suits &= $r['counterparty_id'] == $p_agent_id && $r['project_id'] == $p_project_id;
          break;
        case 'ИЛИ':
          $suits &= $r['counterparty_id'] == $p_agent_id || $r['project_id'] == $p_project_id;
          break;
        case 'И НЕ':
          $suits &= $r['counterparty_id'] == $p_agent_id && $r['project_id'] != $p_project_id;
          break;
      }
      if($r['comment'] != null) {
        switch($r['operand2']) {
          case 'И СОДЕРЖИТ':
            $suits &= str_contains(mb_strtolower($p_description), mb_strtolower($r['comment']));
            break;
          case 'И НЕ СОДЕРЖИТ':
            $suits &= !str_contains(mb_strtolower($p_description), mb_strtolower($r['comment']));
            break;
        }
      }
      if($r['purpose'] != null) {
        switch ($r['operand3']) {
          case 'И СОДЕРЖИТ': //И содержит
            $suits &= str_contains(mb_strtolower($p_purpose), mb_strtolower($r['purpose']));
            break;
          case 'И НЕ СОДЕРЖИТ':
            $suits &= !str_contains(mb_strtolower($p_purpose), mb_strtolower($r['purpose']));
            break;
        }
      }

      if(!$suits) {
        continue;
      }

      $expenseitem = $jsonApi->getObject("expenseitem", $r['expenseitem_id']);
      $href = $expenseitem->meta->href;
      $metahref = $expenseitem->meta->metadataHref;
      $data = "{ \"expenseItem\": {
                   \"meta\": {
                     \"href\": \"$href\",
                     \"metadataHref\": \"$metahref\",
                     \"type\": \"expenseitem\",
                     \"mediaType\": \"application/json\"
                   }
                 }
               }";
      $jsonApi->change($p_type, $p->id, $data);
    }
  }
  $successMessage = 'Статьи расходов обновлены!';
}
catch(Throwable $ex) {
  $errorMessage = 'Ошибка при обновлении статей расходов!';
}

$db->close();
//header('Location: iframe.php');

?>
