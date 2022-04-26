<?php

require_once 'user-context-loader.inc.php';
require_once 'lib.php';
require_once 'PostgreDB.php';
require "db.cfg.php";

$contextName = 'IFRAME';
$db = new PostgreDB($defaultConnection);
$app = AppInstance::loadApp($accountId);

$jsonApi = jsonApi();

if(!isset($loadedAllData))
  $loadedAllData = false;

//Контрагенты
$counterparties = $jsonApi->getObjects("counterparty", $loadedAllData); //Контрагенты (json объект)
$counterpartiesValues = []; //Контрагенты (пары значений id-имя)
foreach($counterparties->rows as $c) {
  $counterpartiesValues[$c->id] = ['id' => $c->id, 'name' => $c->name];
}

//Проекты
$projects = $jsonApi->getObjects("project", $loadedAllData);
$projectsValues = [];
foreach($projects->rows as $p) {
  $projectsValues[$p->id] = ['id' => $p->id, 'name' => $p->name];
}

//Статьи расходов
$expenseitems = $jsonApi->getObjects("expenseitem", $loadedAllData);
$expenseitemsValues = [];
foreach($expenseitems->rows as $e) {
  $expenseitemsValues[$e->id] = ['id' => $e->id, 'name' => $e->name];
}

if(!$loadedAllData && (count($counterparties->rows) < 1000 || count($projects->rows) < 1000 || count($expenseitems->rows) < 1000)) {
  $loadedAllData = true;
}

$operand1Values = ['И', 'ИЛИ', 'И НЕ'];
$operand2Values = ['И СОДЕРЖИТ', 'И НЕ СОДЕРЖИТ'];
$operand3Values = $operand2Values;

$db_rules = $db->select('rules', 'id', "uid = '$accountId'");
$rules = [];
foreach($db_rules as $r) {
  $rules[] = [
    'number' => $r['number'],
    'counterparty' => $counterpartiesValues[$r['counterparty_id']],
    'operand1' => $r['operand1'],
    'project' => $projectsValues[$r['project_id']],
    'operand2' => $r['operand2'],
    'comment' => $r['comment'],
    'operand3' => $r['operand3'],
    'purpose' => $r['purpose'],
    'expenseitem' => $expenseitemsValues[$r['expenseitem_id']],
    'delete_button' => 'Удалить'
  ];
}

$user_period = $db->first('user_periods', 'uid', "uid = '$accountId'");
$previousStart = 'Никогда';
$nextStart = 'Никогда';
$active_period = 1;
if($user_period != null) {
  $previousStart = $user_period['previous_start'];
  $nextStart = $user_period['next_start'];
  $active_period = $user_period['period_id'];
}

$periods = $db->select('periods', 'id');
$periodValues = [];
foreach($periods as $p) {
  $periodValues[$p['id']] = ['id' => $p['id'], 'name' => $p['name'], 'active' => ($p['id'] == $active_period ? true : false)];
}

if(!isset($createPaymentsExpenseitem))
  $createPaymentsExpenseitem = false;

if(!isset($errorMessage))
  $errorMessage = false;

if(!isset($successMessage))
  $successMessage = false;

if(!isset($employee))
  $employee = null;

$db->close();

require 'iframe.html.php';
