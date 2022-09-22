<?php

require_once "connection.php";

class GetModel {
  static public function getData($table, $select, $limit, $assocTables, $assocTypes) {
    if (empty(Connection::getTableColumns($table))) {
      return array(
        "error" => true,
        "message" => "Table '$table' not found"
      );
    };

    $limit = explode(",", $limit);

    if ($assocTables) {
      $join = GetModel::generateJoin($table, $assocTables, $assocTypes);
      $sql = "SELECT $select FROM $table $join";
    } else {
      $sql = "SELECT $select FROM $table LIMIT $limit[0], $limit[1]";
    }
    
    $stmt = Connection::connect()->prepare($sql);

    try {
      $stmt->execute();
      return array(
        "error" => false,
        "data" => $stmt->fetchAll(PDO::FETCH_CLASS)
      );
    } catch (PDOException $e){
      return array(
        "error" => true,
        "message" => $e->getMessage()
      );
    }
  }

  static public function getDataFilter($table, $select, $linkTo, $equalTo, $operator, $limit, $assocTables, $assocTypes) {
    if (empty(Connection::getTableColumns($table))) {
      return array(
        "error" => true,
        "message" => "Table '$table' not found"
      );
    };

    $limit = explode(",", $limit);
    $linkToArr = explode(",", $linkTo);
    $equalToArr = explode(",", $equalTo);
    $conditions = "";

    foreach ($linkToArr as $i => $value) {
      $conditions .= "$value = :$value";
      if ($i != count($linkToArr) - 1) {
        $conditions .= " $operator ";
      }
    }

    if ($assocTables) {
      $join = GetModel::generateJoin($table, $assocTables, $assocTypes);
      $sql = "SELECT $select FROM $table $join WHERE $conditions LIMIT $limit[0], $limit[1]";

    } else {
      $sql = "SELECT $select FROM $table WHERE $conditions LIMIT $limit[0], $limit[1]";
    }

    $stmt = Connection::connect()->prepare($sql);

    foreach ($linkToArr as $i => $value) {
      $stmt->bindParam(":" . $value, $equalToArr[$i], PDO::PARAM_STR);
    }

    try {
      $stmt->execute();
      return array(
        "error" => false,
        "data" => $stmt->fetchAll(PDO::FETCH_CLASS)
      );
    } catch (PDOException $e){
      return array(
        "error" => true,
        "message" => $e->getMessage()
      );
    }
  }

  static public function generateJoin($table, $assocTables, $assocTypes) {
    $assocTablesArr = explode(",", $assocTables);
    $assocTypesArr = explode(",", $assocTypes);
    $join = "";

    
    foreach ($assocTablesArr as $i => $assocTable) {
      $id_assocTable = GetModel::getIdColumn($assocTable);
      if (!$id_assocTable) return "";
      if ($assocTable == $table) continue;

      if (empty($assocTypesArr[$i])) $assocTypesArr[$i] = "INNER";

      if ($i == 0) $indexAssocTable = $table;
      else $indexAssocTable = $assocTablesArr[$i - 1];

      $join .= " $assocTypesArr[$i] JOIN $assocTable ON $indexAssocTable.$id_assocTable = $assocTable.$id_assocTable";
    }

    return $join;
  }

  static public function getIdColumn($table) {
    $columns = Connection::getTableColumns($table);
    foreach ($columns as $column) {
      if (preg_match("/^id_/", $column)) {
        $id = explode("_", $column)[1];

        if (preg_match("/$id/i", $table)) {
          return $column;
        }
      }
    }
  }
}