<?php

require_once "lib.php";

if(cfg()->release) {
  $contextKey = $_GET['contextKey'];
  loginfo($contextName ?: "IFRAME", "Loaded iframe with contextKey: $contextKey");
  $employee = vendorApi()->context($contextKey);

  $uid = $employee->uid;
  $fio = $employee->shortFio;
  $accountId = $employee->accountId;

  $isAdmin = $employee->permissions->admin->view;
} else {
  $uid = null;
  $fio = null;
  $accountId = 10;
  $isAdmin = false;
}
