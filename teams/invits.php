<?php

include_once '../includes.php';
include_once '../inc/auth.inc.php';
include_once 'common.php';

if ( isset($_POST['accept']) || isset($_POST['refuse']) ) {
  $invits = isset($_POST['invit']) ? array_map("intval", $_POST['invit']) : array();
  if ( count($invits) ) {
    if ( isset($_POST['accept']) ) {
      $db->query("UPDATE users_teams SET status = '1' WHERE teamid IN (".implode(", ", $invits).") AND userid = '{$user['uid']}'");
      if ( count($invits) > 1 ) {
        $ok[] = "Les invitations ont bien été acceptées";
      }
      else {
        $ok[] = "L'invitation a bien été acceptée";
      }
    }
    elseif (isset($_POST['refuse'])) {
      $db->query("DELETE FROM users_teams WHERE teamid IN (".implode(", ", $invits).") AND userid = '{$user['uid']}'");
      if ( count($invits) > 1 ) {
        $ok[] = "Les invitations ont bien été refusées";
      }
      else {
        $ok[] = "L'invitation a bien été refusée";
      }
    }
  }
  else {
    $errors[] = "Vous n'avez coché aucune invitation";
  }
}


$sql = $db->query("SELECT t.teamid, t.name as teamname, u.username as admin FROM users_teams ut "
                  ."JOIN teams t ON t.teamid = ut.teamid "
                  ."JOIN users u ON u.uid = t.userid "
                  ."WHERE ut.userid = '{$user['uid']}' AND status = '0'");

$invits = array();
while ( $invit = mysql_fetch_assoc($sql) ) {
  $invits[] = $invit;
}

// Mettre à jour compteur d'invitations
$user['invits'] = count($invits);
$smarty->assign("user", $user);

$smarty->assign("invits", convert($invits));
$smarty->assign("errors", $errors);
$smarty->assign("ok", $ok);
$smarty->display("teams/invits.tpl");