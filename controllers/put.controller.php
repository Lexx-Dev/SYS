<?php

include_once "models/put.model.php";

class PutController {
  static public function putData($table, $rows) {
    $response = PutModel::putData($table, $rows);
    $return = new PutController();
    $return->setResponse($response);
  }

  public function setResponse($response)
  {
    if ($response["error"] == false) {
      $json = array(
        "status" => 200,
        "message" => $response["message"],
        "data_update" => $response["data"]
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
