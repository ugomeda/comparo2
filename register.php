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
$username = "";

if ( isset($_POST['register']) ) {
  $pwd1 = trim($_POST['password_1']);
  $pwd2 = trim($_POST['password_2']);
  $username = $_POST['username'];
  
  try {
    // CHeck password
    if ( $pwd1 != $pwd2 ) throw new Exception("Les mots de passe sont différents");
    if ( strlen($pwd1) < 6 ) throw new Exception("Votre mot de passe doit faire 6 caractères minimum");
    
    // Check username
    if ( preg_match("/[^a-zA-Z0-9_\-]/", $username) ) throw new Exception("Les caractères non autorisés dans le nom d'utilisateur");
    if ( strlen($username) < 2 ) throw new Exception("Nom d'utilisateur trop court");
    
    $sql = $db->query("SELECT uid FROM users WHERE username = '{$username}'");
    if ( $db->num_rows($sql) ) throw new Exception("Nom d'utilisateur déjà pris");
    
    // Insert
    $db->insert("users", array("username" => $username, "email" => null, "password" => sha1(trim($pwd1).$config['grain_de_sel'])));
    header("location:register.php?created=ok");
    exit;
    
  } catch ( Exception $e ) {
    $smarty->assign("error", $e->getMessage());
  }
}

if ( isset($_GET['created']) ) $smarty->assign("ok", "Compte créé");

$smarty->assign("username", convert($username));

$smarty->display("register.tpl");