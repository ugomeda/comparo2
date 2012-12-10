<?php

$smarty->template_dir = "../templates";
$smarty->compile_dir = "../templates_c";
$smarty->assign("css", array("styles/teams.css"));
$smarty->assign("js", array("js/jquery.min.js"));
$smarty->assign("path", "../");
$user = auth::check_connected();
$smarty->assign("user", $user);
$errors = array();
$ok = array();
$alerts = array();

function get_team_infos($tid, $infos) {
  global $db;
  $sql = $db->query("SELECT ".implode(", ", $infos)." FROM teams WHERE teamid = '".intval($tid)."'");
  return mysql_fetch_assoc($sql);
}

function get_sub() {
  global $smarty, $db, $user;
  
  include_once '../inc/form.inc.php';
  $subid = get_get_val("sid", 0, "int");
  if ( $subid == 0 ) $subid = get_post_val("sid", 0, "int");
  if ( count($user['teams']) ) {
    $sql = $db->query("SELECT s.subid, t.userid, t.teamid, s.episode, s.episodename, s.saison, t.name, u.username, s.timing FROM subtitles s "
                     ."JOIN teams t ON t.teamid = s.teamid "
                     ."JOIN users u ON u.uid = t.userid "
                     ."WHERE s.subid = '{$subid}' AND t.teamid IN (".implode(", ", $user['teams']).")");

    $sub = mysql_fetch_array($sql);
  }
  if ( ! $sub ) show_error("Sous-titre inaccessible");
  
  $steps = array();
  $sql = $db->query("SELECT `index`, name FROM teams_steps WHERE teamid = '{$sub['teamid']}'");
  while ( $step = mysql_fetch_assoc($sql) ) {
    $steps[$step['index']] = $step['name'];
  }
  $sub['steps'] = $steps;
  
  $smarty->assign("sub", convert($sub));
  return $sub;
}