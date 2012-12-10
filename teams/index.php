<?php

include_once '../includes.php';
include_once '../inc/auth.inc.php';
include_once 'common.php';

// Subtitles
$subtitles = array();
if ( count($user['teams']) ) {
  $sql = $db->query("SELECT s.subid, s.episode, s.saison, s.episodename, t.userid, DATE_FORMAT(s.created, '%d/%m/%y') as created, "
                    ."t.name, t.teamid FROM subtitles s "
                    ."JOIN teams t ON t.teamid = s.teamid "
                    ."WHERE s.status = '1' AND t.teamid IN (".implode(", ", $user['teams']).") "
                    ."ORDER BY created DESC");
                    

  while ( $sub = mysql_fetch_array($sql) ) {
    $sub['admin'] = $sub['userid'] == $user['uid'];
    $subtitles[] = $sub;
  }
}

$smarty->assign("subtitles", convert($subtitles));

$smarty->assign("errors", $errors);
$smarty->assign("ok", $ok);
$smarty->display("teams/index.tpl");