<?php

class db {
  var $config = array();
  var $connexion = null;
  
  function db() {
    global $config;
    
    $this->config = $config;
    
    $this->connexion = mysql_connect($this->config['mysql_server'], $this->config['mysql_user'], $this->config['mysql_pass']) or die("Connection au serveur SQL impossible");
    mysql_select_db($this->config['mysql_db'], $this->connexion) or die("Impossible de selectionner la base de donnees");  
  }
  
  function query($query) {
    $sql = mysql_query($query, $this->connexion) or die(mysql_error());
    return $sql;
  }
  
  function insert($table, $data) {
    $data = array_map('mysql_real_escape_string', $data);
    
    $champs = implode(", ", array_keys($data));
    $values = '"'.implode('", "', $data).'"';
    
    return $this->query("INSERT INTO {$table} ({$champs}) VALUES ({$values})");
  }

  function update($table, $data, $where = false) {
    $data = array_map('mysql_real_escape_string', $data);
    
    $values = array();
    foreach ( $data as $champ => $value ) {
      $values[] = $champ." = '{$value}'";
    }
    
    $values = implode(', ', $values);
    return $this->query("UPDATE {$table} SET {$values}".($where ? " WHERE ".$where : ""));
  }
  
  function insertid() {
    return mysql_insert_id($this->connexion);
  }
  
  function affectedrows() {
    return mysql_affected_rows($this->connexion);
  }
  
  function free($req) {
    return mysql_free_result($req);
  }
  
  function escape($str) {
    return mysql_real_escape_string($str);
  }
  
  function fetch_assoc($query) {
    return mysql_fetch_assoc($query);
  }
  
  function num_rows($query) {
    return mysql_num_rows($query);
  }
  
}