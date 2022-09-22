<?php

include_once "controllers/get.controller.php";

$select = $_GET["select"] ?? "*";
$linkTo = $_GET["linkTo"] ?? null;
$equalTo = $_GET["equalTo"] ?? null;
$operator = $_GET["operator"] ?? "AND";
$limit = $_GET["limit"] ?? "0, 99";
$assocTables = $_GET["assocTables"] ?? null;
$assocTypes = $_GET["assocTypes"] ?? "INNER";

$response = new GetController();

if ($linkTo && $equalTo) {
  $response -> getDataFilter($table, $select, $linkTo, $equalTo, $operator, $limit, $assocTables, $assocTypes);
} else {
  $response -> getData($table, $select, $limit, $assocTables, $assocTypes);
}
