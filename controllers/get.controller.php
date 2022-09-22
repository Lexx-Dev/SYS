<?php
include_once "models/get.model.php";

class GetController {
  static public function getData($table, $select, $limit, $assocTables, $assocTypes) {
    $response = GetModel::getData($table, $select, $limit, $assocTables, $assocTypes);
    $return = new GetController();
    $return -> setResponse($response);
  }

  static public function getDataFilter($table, $select, $linkTo, $equalTo, $operator, $limit, $assocTables, $assocTypes) {
    $response = GetModel::getDataFilter($table, $select, $linkTo, $equalTo, $operator, $limit, $assocTables, $assocTypes);
    $return = new GetController();
    $return -> setResponse($response);
  }

  public function setResponse($response) {
    if ($response["error"] == false) {
      $json = array(
        "status" => 200,
        "total" => count($response["data"]),
        "results" => $response["data"]
      );
    } else {
      $json = array(
        "status" => 404,
        "error" => $response["message"],
      );
    }

    echo json_encode($json, http_response_code($json['status']));
    return;
  }
}