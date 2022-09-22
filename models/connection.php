<?php

class Connection {
  static public function infoDatabase() {
    $infoDB = [
      "database" => "qnoj6rd8pftwfmxv",
      "user" => "d5lq6hfkkb9vajha",
      "pass" => "i17ofz67tvc3jcrd"
    ];

    return $infoDB;
  }

  static public function connect() {
    try {
      $link = new PDO(
        "mysql:host=pxukqohrckdfo4ty.cbetxkdyhwsb.us-east-1.rds.amazonaws.com;dbname=".Connection::infoDatabase()["database"],
        Connection::infoDatabase()["user"],
        Connection::infoDatabase()["pass"],
      );

      $link->exec("set names utf8");

    } catch(PDOException $e) {
      die("Error: " .$e->getMessage());
    }

    return $link;
  }

  static public function getTableColumns($table) {
    $database = Connection::infoDatabase()["database"];

    return Connection::connect()
    ->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = '$table'")
    ->fetchAll(PDO::FETCH_COLUMN);
  }
}