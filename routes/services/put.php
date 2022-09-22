<?php

require_once "controllers/put.controller.php";

$rows = json_decode(file_get_contents('php://input'), true);

$response = new PutController();
$response -> putData($table, $rows);