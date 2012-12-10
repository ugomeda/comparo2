<?php

include_once '../includes.php';
include_once '../inc/auth.inc.php';
include_once '../inc/form.inc.php';
include_once 'common.php';

$sub = get_sub();
//$alerts[] = "Le dispatch par timing est désactivé car vous n'avez pas défini le timing approximatif de l'épisode.";

if ( get_get_val("action") == "updated" ) {
  $ok[] = "Le dispatch a bien été mis à jour";
}


// Get jobs
$sql = $db->query("SELECT * FROM dispatch d "
                ."JOIN users u ON d.userid = u.uid "
                ."JOIN dispatch_modes m ON m.subid = d.subid AND m.index = d.step "
                ."WHERE d.subid = '{$sub['subid']}' "
                ."ORDER BY d.step ASC, d.from ASC");

$jobs = array_fill(0, 5, array());

while ( $job = mysql_fetch_array($sql) ) {
  $jobs[$job['step']][] = $job;
}


$smarty->assign("ok", $ok);
$smarty->assign("jobs", convert($jobs));
$smarty->assign("alerts", $alerts);
$smarty->display("teams/dispatch.tpl");