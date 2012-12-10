<?php

include_once '../includes.php';
include_once '../inc/auth.inc.php';
include_once 'common.php';

$errors = array();
$ok = array();

if ( isset($_GET['action']) && $_GET['action'] == "added" ) {
  $ok[] = "Le sous-titre a bien été créé";
}

$tid = isset($_GET['tid']) ? intval($_GET['tid']) : (isset($_POST['tid']) ? intval($_POST['tid']) : 0);

// Check access
if ( ! in_array($tid, $user['teams'], true) ) {
  show_error("Vous ne pouvez pas accéder à cette team.");
}

// Team
$sql = $db->query("SELECT teamid, name, t.userid, u.username FROM teams t JOIN users u ON u.uid = t.userid WHERE teamid = '{$tid}'");
$team = mysql_fetch_assoc($sql);

$isadmin = $team['userid'] == $user['uid'];
$smarty->assign("isadmin", $isadmin);

// Remove membre
if ( isset($_POST['delete']) && $isadmin ) {
  $members = isset($_POST['member']) ? array_map("intval", $_POST['member']) : array();
  if ( count($members) ) {
    $db->query("DELETE FROM users_teams WHERE teamid = '{$tid}' AND userid IN (".implode(", ", $members).")");
    if ( count($members) > 1 ) {
      $ok[] = "Les membres ont bien été retirés de la team";
    }
    else {
      $ok[] = "Le membre a bien été retiré de la team";
    }
  }
  else {
    $errors[] = "Vous n'avez sélectionné aucun membre";
  }
}

// Invite a member
if ( isset($_POST['invit']) && $isadmin ) {
  $sql = $db->query("SELECT uid FROM users WHERE username = '".mysql_real_escape_string($_POST['invit'])."' LIMIT 1");
  if ( mysql_num_rows($sql) ) {
    $user = mysql_result($sql, 0);
  
    $sql = $db->query("SELECT t.teamid FROM teams t LEFT OUTER JOIN users_teams ut ON t.teamid = ut.teamid WHERE t.teamid = '{$tid}' AND (ut.userid = '{$user}' OR t.userid = '{$user}') LIMIT 1");
    if ( mysql_num_rows($sql) > 0 ) {
      $errors[] = "Cet utilisateur appartient déjà à cette team";
    }
    else {
      $db->insert("users_teams", array("teamid" => $tid, "userid" => $user, "status" => 0));
      $ok[] = "Utilisateur invité";
    }
  }
  else {
    $errors[] = 'Utilisateur inconnu';
  }
}

$members = array(array("name" => $team['username'], "role" => 2, "uid" => false));

// List members
$sql = $db->query("SELECT u.uid, u.username, ut.status FROM users_teams ut "
                ."JOIN users u ON ut.userid = u.uid "
                ."WHERE ut.teamid = '{$tid}'");

while ( $member = mysql_fetch_array($sql) ) {
  $members[] = array("name" => $member['username'], "role" => $member['status'], "uid" => $member['uid']);
}


$smarty->assign("team", convert($team));
$smarty->assign("members", convert($members));
$smarty->assign("errors", $errors);
$smarty->assign("ok", $ok);
$smarty->display("teams/members.tpl");