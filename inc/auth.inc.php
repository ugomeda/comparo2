<?php

class User {
  var $_userData = false;
  var $_teams = array();

  function User() {
    global $db;

    if ( isset($_COOKIE['comparoUid']) && isset($_COOKIE['comparoPass']) ) {
      $uid = intval($_COOKIE['comparoUid']);
      $pass = $db->escape($_COOKIE['comparoPass']);
      $sql = $db->query("SELECT u.uid, u.username, u.email, i.invits FROM users u "
                        ."LEFT OUTER JOIN (SELECT COUNT(*) as invits, userid FROM users_teams WHERE status = '0' GROUP BY userid) i ON i.userid = u.uid "
                        ."WHERE uid = '{$uid}' AND password = '{$pass}'");
      $this->_userData = mysql_fetch_assoc($sql);
      
      
      if ( $this->_userData ) {
        $sql = $db->query("SELECT t.teamid FROM teams t LEFT OUTER JOIN users_teams ut ON ut.teamid = t.teamid WHERE t.userid = '{$uid}' OR (ut.userid = '{$uid}' and ut.status = '1')");
        while ( $t = mysql_fetch_assoc($sql) )
          $this->_teams[] = intval($t['teamid']);
      }
    } 
  }
  
  function isLogged() {
    return ($this->_userData !== false);
  }
  
  function username() {
    return $this->_userData['username'];
  }
  
  function check_connected() {
    if ( ! $this->_teams ) {
      echo "Vous n'etes pas identifie";
      exit;
    }
  }
}