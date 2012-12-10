<?php

include_once '../includes.php';
include_once '../inc/auth.inc.php';
include_once 'common.php';

if ( isset($_GET['action']) && $_GET['action'] == "added" ) {
  $ok[] = "La team a bien été ajoutée";
}

$teams = array();
if ( count($user['teams']) ) {
  $sql = $db->query("SELECT t.userid, t.teamid, t.name, c1.count_all, c2.count_actif, s.episode, s.saison, s.episodename, DATE_FORMAT(s.created,'%d/%m/%y') as created, s.subid FROM teams t "
                    ."LEFT OUTER JOIN (SELECT COUNT(*) as count_all, teamid FROM subtitles GROUP BY teamid) c1 ON c1.teamid = t.teamid "
                    ."LEFT OUTER JOIN (SELECT COUNT(*) as count_actif, teamid FROM subtitles WHERE status = '1' GROUP BY teamid) c2 ON c2.teamid = t.teamid "
                    ."LEFT OUTER JOIN (SELECT * FROM (SELECT subid, episode, saison, episodename, created, teamid FROM subtitles ORDER BY status DESC, created DESC) _s GROUP BY teamid) s ON s.teamid = t.teamid "
                    ."WHERE t.teamid IN (".implode(", ", $user['teams']).")");

  while ( $t = mysql_fetch_assoc($sql) ) {
    $team = array("name" => $t['name'],
                  "id" => $t['teamid'],
                  "count_all" => intval($t['count_all']),
                  "count_actif" => intval($t['count_actif']),
                  "episode" => $t['episode'],
                  "saison" => $t['saison'],
                  "episodename" => convert($t['episodename']),
                  "created" => $t['created'],
                  "subid" => $t['subid'],
                  "admin" => $t['userid'] == $user['uid']
                  );
    $teams[] = convert($team);
  }
}
$smarty->assign("teams", $teams);


$smarty->assign("errors", $errors);
$smarty->assign("ok", $ok);
$smarty->display("teams/myteams.tpl");