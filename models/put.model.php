<?php

include_once "models/connection.php";

class PutModel
{
  static public function putData($table, $rows)
  {
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

    foreach ($rows as $row) {
      $values = [];

      foreach ($columns as $column) {
        $match = explode("_", $column)[1] ?? null;
        if (!empty($match)) {
          if (preg_match("/$match/i", $table)) {
            $tableID = $column;
            continue;
          };
        }

        if (isset($row[$column])) {
          $values[$column] = $row[$column];
        }
      }

      $updateQuery = "";
      foreach ($values as $key => $value) {
        $updateQuery .= "$key = '$value', ";
      }
      $updateQuery = substr($updateQuery, 0, -2);

      $sql = "UPDATE $table SET $updateQuery WHERE $tableID = $row[$tableID]";
      $stmt = Connection::connect()->prepare($sql);

      try {
        $stmt->execute();

        $lastId = Connection::connect()->prepare("SELECT * FROM $table WHERE $tableID = $row[$tableID]");
        $lastId->execute();
        $satisfactory[] = $lastId->fetchAll(PDO::FETCH_CLASS);
      } catch (PDOException $e) {
        $errors[] = array(
          "message" => $e->getMessage()
        );
      }

      if (empty($errors)) {
        return array(
          "error" => false,
          "message" => empty($satisfactory) ? "No data updated" : "Data updated successfully",
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
