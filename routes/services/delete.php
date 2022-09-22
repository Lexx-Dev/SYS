<?php

require_once "controllers/delete.controller.php";

$Ids = json_decode(file_get_contents('php://input'), true);

$response = new DeleteController();
$response -> deleteData($table, $Ids);