<?php

// On ne met pas de cache, les informations changent tout le temps
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date du passé
header('Content-Type: text/html; charset=utf-8');
include_once 'config.inc.php';

include_once 'inc/smarty/Smarty.class.php';
include_once 'inc/utils.php';
include_once 'inc/magic_quotes.lib.php';
include_once 'inc/db.inc.php';
include_once 'inc/auth.inc.php';

$db = new db();
$user = new User();
$smarty = new Smarty();

$smarty->assign_by_ref("user", $user);

function show_error($msg, $url = "index.php", $exit = true) {
  global $smarty, $useAPI;
  
  if ( isset($useAPI) && $useAPI ) {
    echo "ERR:".$msg;
    if ( $exit ) exit;
  }
  
  $smarty->assign("msg", $msg);
  $smarty->assign("url", $url);
  $smarty->display('error.tpl');
  if ( $exit ) exit;
}

