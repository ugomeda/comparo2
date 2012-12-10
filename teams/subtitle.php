<?php

include_once '../includes.php';
include_once '../inc/auth.inc.php';
include_once 'common.php';

$sub = get_sub();


$smarty->assign("errors", $errors);
$smarty->assign("ok", $ok);
$smarty->assign("serieName", convert($sub['name']));
$smarty->assign("serieName", convert($sub['name']));
$smarty->assign("episodeName", $sub['saison']."&times;".$sub['episode']." &#147;".convert($sub['episodename'])."&#148;");

$smarty->display("teams/subtitle.tpl");