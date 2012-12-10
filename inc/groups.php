<?php

// Trouver dernière date de visite
// Renvoie false si première visite
function last_visit($groupId) {
  $date = get_lastvisits();
  if ( isset($date[$groupId]) ) {
    return $date[$groupId];
  }
  return false;
}

function get_lastvisits() {
  $groups = explode("\n", isset($_COOKIE['comparoGroups']) ? $_COOKIE['comparoGroups'] : "" );
  
  $retour = array();
  foreach($groups as $group) {
    if ( preg_match("#^([a-zA-Z0-9]+),([0-9]+)$#", trim($group), $matches) ) {
      $retour[$matches[1]] = $matches[2];
    }
  }
  
  return $retour;
}

function addvisit($groupId, $date = false) {
  if ( $date === false ) $date = time();
  
  $data = get_lastvisits();
  $data[$groupId] = $date;
  
  $cookie = array();
  foreach($data as $groupId => $date) {
    $cookie[] = $groupId.",".$date;
  }
  
  setcookie("comparoGroups", implode("\n", $cookie), (time() + 60*60*24*30*2));
}