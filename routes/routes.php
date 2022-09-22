<?php

$routesArr = array_filter(explode("/", $_SERVER['REQUEST_URI']));

if (empty($routesArr)) {
  $json = array(
    "status" => 404,
    "result" => "Not found"
  );
  
  echo json_encode($json, http_response_code($json['status']));
  return;
} else if (isset($_SERVER['REQUEST_METHOD'])) {
  $table = explode('?', $routesArr[1])[0];

  if ($_SERVER['REQUEST_METHOD'] == "GET") {
    include "services/get.php";
  }

  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    include "services/post.php";
  }

  if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    include "services/put.php";
  }
  
  if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    include "services/delete.php";
  }
}