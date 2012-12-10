<?php

include_once '../includes.php';
include_once '../inc/auth.inc.php';
include_once 'common.php';

$smarty->assign("css", array("styles/teams.css"));
$smarty->assign("js", array("js/jquery.min.js"));

if ( isset($_GET['action']) && $_GET['action'] == "added" ) {
  $ok[] = "Le sous-titre a bien été créé";
}

$tid = isset($_GET['tid']) ? intval($_GET['tid']) : 0;

// Check access
if ( ! in_array($tid, $user['teams'], true) ) {
  show_error("Vous ne pouvez pas accéder à cette team.");
}

// Team
$team = get_team_infos($tid, array("name", "userid"));

$isadmin = $team['userid'] == $user['uid'];

// Subtitles
if ( $team ) {
  $sql = $db->query("SELECT subid, episode, saison, episodename, status, DATE_FORMAT(created, '%d/%m/%y') as created FROM subtitles WHERE teamid = '{$tid}' ORDER BY created DESC");
  $subtitles = array();
  while ( $sub = mysql_fetch_array($sql) ) {
    $subtitles[] = $sub;
  }

}


$smarty->assign("team", convert($team));
$smarty->assign("subtitles", convert($subtitles));
$smarty->assign("errors", $errors);
$smarty->assign("ok", $ok);
$smarty->display("teams/subtitles.tpl");