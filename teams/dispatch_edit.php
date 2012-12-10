<?php

include_once '../includes.php';
include_once '../inc/auth.inc.php';
include_once 'common.php';

$smarty->assign("css", array("styles/teams.css", "js/theme/ui.all.css"));
$smarty->assign("js", array("js/jquery-1.3.1.min.js", "js/ui/ui.core.min.js", "js/ui/ui.slider.min.js", "js/ui/ui.sortable.min.js", "js/team/dispatch.js"));


$sub = get_sub();

$index = get_get_val("step");
$index = set_in_range($index, 0, 4, 0);

if ( isset($_POST['set_dispatch']) ) {
  $mode = get_post_val("mode", 0);
  $mode = set_in_range($mode, 0, 1, 0);

  $step = get_post_val("step");
  $step = set_in_range($step, 0, 4, 0);

  $db->query("INSERT INTO dispatch_modes (subid,`index`,mode) VALUES ('{$sub['subid']}','{$step}','{$mode}') "
                       ."ON DUPLICATE KEY UPDATE mode='{$mode}'");

  $db->query("DELETE FROM dispatch WHERE step = '{$step}' AND subid = '{$sub['subid']}'");
  
  $i = 0;
  while ( $uid = get_post_val("job_uid", false, false, $i) ) {
    $db->insert("dispatch", array("userid" => $uid,
                                  "`index`" => $i,
                                  "subid" => $sub['subid'],
                                  "`from`" => set_in_range(get_post_val("job_from", 0, false, $i), 0, 100, 0),
                                  "`to`" => set_in_range(get_post_val("job_to", 0, false, $i), 0, 100, 0),
                                  "step" => $step));
    $i++;
  }
  
  header("location:dispatch.php?sid={$sub['subid']}&action=updated");
  exit;
}

// mode
$mode = 0;
$sql = $db->query("SELECT mode FROM dispatch_modes WHERE `index` = {$index} AND subid = '{$sub['subid']}' AND mode = '1'");

if ( mysql_num_rows($sql) ) $mode = 1;

// Jobs
$js_data = array();
$sql = $db->query("SELECT * FROM dispatch d JOIN users u ON u.uid = d.userid WHERE step = '{$index}' AND subid = '{$sub['subid']}' ORDER BY `index` ASC");
$jobs = array();
while ( $user = mysql_fetch_assoc($sql) ) {
  $jobs[] = $user;
  $js_data[] = "[{$user['uid']},\"".addslashes($user['username'])."\",{$user['from']},{$user['to']}]";
}

// Users
$users = array();
$users[] = array(intval($sub['userid']), $sub['username']);

// List members
$sql = $db->query("SELECT u.uid, u.username FROM users_teams ut "
                ."JOIN users u ON ut.userid = u.uid "
                ."WHERE ut.teamid = '{$sub['teamid']}' AND status = '1' ORDER BY username ASC");
while ( $user = mysql_fetch_assoc($sql) ) {
  $users[] = array(intval($user['uid']), $user['username']);
}

$smarty->assign("jobs", convert($jobs));
$smarty->assign("users", convert($users));
$smarty->assign("step", $index);
$smarty->assign("alerts", $alerts);
$smarty->assign("js_head", "var dispatch = [".implode(",",$js_data)."];\nvar mode = {$mode};\nvar timing = {$sub['timing']};");
$smarty->display("teams/dispatch_edit.tpl");