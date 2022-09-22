<?php

include_once "models/connection.php";

class DeleteModel {
  static public function deleteData($table, $Ids) {
    $columns = Connection::getTableColumns($table);
    if (empty($columns)) {
      return array(
        "error" => true,
        "message" => "Table '$table' not found"
      );
    };

    $tableID = null;
    $errors = [];
    $satisfactory = [];

    //extract tableID
    foreach ($columns as $column) {
      $match = explode("_", $column)[1] ?? null;
      if (!empty($match)) {
        if (preg_match("/$match/i", $table)) {
          $tableID = $column;
          break;
        }
      }
    }

    foreach ($Ids as $Id) {
      //verificar si existe el id
      $sql = "SELECT * FROM $table WHERE $tableID = $Id";
      $stmt = Connection::connect()->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetchAll(PDO::FETCH_CLASS);
      if (empty($row)) {
        $errors[] = array(
          "message" => "The id $Id does not exist in the table $table"
        );
        continue;
      }

      $sql = "DELETE FROM $table WHERE $tableID = $Id";
      $stmt = Connection::connect()->prepare($sql);

      try {
        $stmt->execute();
        $satisfactory[] = $row;
      } catch (PDOException $e) {
        $errors[] = array(
          "message" => $e->getMessage()
        );
      }

      print_r("this errors-> $errors");
      if (empty($errors)) {
        return array(
          "error" => false,
          "message" => "Data eliminated successfully",
          "data" => $satisfactory
        );
      } else {
        return array(
          "error" => true,
          "message" => $errors
        );
      }
    }
  }
}