<?php

include_once "models/post.model.php";

class PostController {
  static public function postData($table, $rows) {
    $response = PostModel::postData($table, $rows);
    $return = new PostController();
    $return -> setResponse($response);
  }

  public function setResponse($response) {
    if ($response["error"] == false) {
      $json = array(
        "status" => 200,
        "message" => $response["message"],
        "data_insert" => $response["data"]
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