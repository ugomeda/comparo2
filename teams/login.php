<?php

include_once '../includes.php';

$username = mysql_real_escape_string(trim($_POST['username']));
$password = sha1(trim($_POST['password']).$config['grain_de_sel']);

$sql = $db->query("SELECT uid FROM users WHERE username = '{$username}' AND password = '{$password}'");

$user = mysql_fetch_assoc($sql);
if ( $user ) {
  setcookie("comparoUid", $user['uid'], time()+60*60*24*30, $config['cookie_path']);
  setcookie("comparoPass", $password, time()+60*60*24*30, $config['cookie_path']);
  header("location:index.php");
  exit;
}

echo "LOGIN INVALIDE";