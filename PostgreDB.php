<?php

class PostgreDB {
  public $connectionString;
  private $connection;

  public function __construct($connectionString) {
    $this->connectionString = $connectionString;
    $this->connection = pg_connect($connectionString);
  }

  public function open() {
    $this->connection = pg_connect($this->connectionString);
  }

  public function close() {
    pg_close($this->connection);
  }

  public function select($table_name, $id_colname, $condition = null) {
    $res = [];
    $query = "SELECT * FROM $table_name";
    if($condition != null)
      $query = $query . " WHERE $condition";
    $query_result = pg_query($this->connection, $query) or die('Ошибка запроса: ' . pg_last_error());
    while ($line = pg_fetch_array($query_result, null, PGSQL_ASSOC)) {
        $res[$line[$id_colname]] = $line;
    }
    return $res;
  }

  public function first($table_name, $id_colname, $condition = null) {
    $select_res = $this->select($table_name, $id_colname, $condition);
    if($select_res == null)
      return null;
    return $select_res[array_keys($select_res)[0]];
  }

  public function count($table_name, $condition = null) {
    $query = "SELECT count(*) as cnt FROM $table_name";
    if($condition != null)
      $query = $query . " WHERE $condition";
    $query_res = pg_query($this->connection, $query);
    return pg_fetch_row($query_res)[0];
  }

  public function delete($table_name, $condition = null) {
    $query = "DELETE FROM $table_name";
    if($condition != null)
      $query = $query . " WHERE $condition";
    return pg_query($this->connection, $query);
  }

  public function column($table_name, $col_name, $condition = null) {
    $query = "SELECT $col_name FROM $table_name";
    if($condition != null)
      $query = $query . " WHERE $condition";
    $query_res = pg_query($this->connection, $query);
    return pg_fetch_all_columns($query_res);
  }

  public function update($table_name, $values, $condition) {
    pg_update($this->connection, $table_name, $values, $condition);
  }

  public function insert($table_name, $data) {
    pg_insert($this->connection, $table_name, $data);
  }
}
?>
