<?php

include_once "models/connection.php";

class PostModel
{
  public static function postData($table, $rows)
  {
    $columns = Connection::getTableColumns($table);

    if (empty($columns)) {
      return array(
        "error" => true,
        "message" => "Table '$table' not found"
      );
    };

    $tableID = "";
    $errors = [];
    $satisfactory = [];

    foreach ($rows as $row) {
      $values = [];

      //validate columns and extract tableID
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
        } else {
          $key = key($column);
          return array(
            "error" => true,
            "message" => "The field '$key' is required"
          );
        }
      }

      $insertColumns = implode(", ", array_keys($values));
      $insertValues = implode("', '", $values);

      $sql = "INSERT INTO $table ($insertColumns) VALUES ('$insertValues')";
      $stmt = Connection::connect()->prepare($sql);

      try {
        $stmt->execute();

        $lastId = Connection::connect()->prepare("SELECT * FROM $table ORDER BY $tableID DESC LIMIT 1");
        $lastId->execute();
        $satisfactory[] = $lastId->fetchAll(PDO::FETCH_CLASS);
      } catch (PDOException $e) {
        $errors[] = array(
          "message" => $e->getMessage()
        );
      }
    }

    if (empty($errors)) {
      return array(
        "error" => false,
        "message" => "Data inserted successfully",
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
