<?php

require_once "controllers/post.controller.php";

$rows = json_decode(file_get_contents('php://input'), true);
print_r($rows);
return;

$response = new PostController();
$response -> postData($table, $rows);