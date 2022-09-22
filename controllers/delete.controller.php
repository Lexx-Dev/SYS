<?php

include_once "models/delete.model.php";

class DeleteController {
  static public function deleteData($table, $Ids) {
    $response = DeleteModel::deleteData($table, $Ids);
    $return = new DeleteController();
    $return->setResponse($response);
  }

  public function setResponse($response) {
    if ($response["error"] == false) {
      $json = array(
        "status" => 200,
        "message" => $response["message"],
        "data_eliminated" => $response["data"]
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