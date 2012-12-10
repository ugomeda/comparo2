<?php

include_once 'includes.php';

$js = array("js/jquery.min.js",
            "js/ui/effects.core.min.js",
            "js/ui/ui.core.min.js",
            "js/ui/effects.blind.min.js", 
            "js/jquery.cookies.js",
            "js/comparo-options.js",
            "js/comparo-menuindex.js",);

$smarty->assign("js", $js);
$smarty->assign("css", array("styles/comparo.css"));

if ( isset($_GET['disconnect']) ) {
  setcookie('comparoUid', '', -1, $config['cookie_path']);
  setcookie('comparoPass', '', -1, $config['cookie_path']);
  header("location:login.php");
  exit;
}

if ( $user->isLogged() ) { header("location:index.php"); exit; }


if ( isset($_POST['login']) ) {
  $pwd = sha1(trim($_POST['password']).$config['grain_de_sel']);
  $username = $db->escape($_POST['username']);
  
  try {
    $sql = $db->query("SELECT uid FROM users WHERE username = '{$username}' AND password = '{$pwd}'");
    $data = mysql_fetch_assoc($sql);
    
    if ( ! $data ) throw new Exception("Logins invalides");

    setcookie("comparoUid", $data['uid'], time()+60*60*24*30, $config['cookie_path']);
    setcookie("comparoPass", $pwd, time()+60*60*24*30, $config['cookie_path']);
    header("location:index.php");
    exit;
  } catch ( Exception $e ) {
    $smarty->assign("error", $e->getMessage());
  }
}

$smarty->display("login.tpl");