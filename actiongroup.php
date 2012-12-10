<?php

include_once 'includes.php';
include_once 'inc/key.php';

if ( isset($_POST['addComparo']) ) {
  $idGroup = isset($_POST['groupId']) ? filter($_POST['groupId']) : false;
  $url = isset($_POST['comparoId']) ? $_POST['comparoId'] : 0;
  
  //Check group
  $sql = $db->query("SELECT id FROM groups WHERE id = '{$idGroup}'");
  $group = mysql_fetch_assoc($sql);
  if ( ! $group )
    show_error("Le groupe spécifié n'existe pas.", "index.php");
  
  // Check comparo
  if ( ! preg_match("#view\-([a-z0-9A-Z]*).html$#", $url, $matches) )
    show_error("L'adresse donnée est invalide", $idGroup != false ? "group-".$idGroup.".html" : "index.php");
    
  $comparoId = $matches[1];
  
  $sql = $db->query("SELECT id FROM comparos WHERE id = '{$comparoId}'");
  $comparo = mysql_fetch_assoc($sql);
  if ( ! $comparo )
    show_error("Le Comparo envoyé n'existe pas.", $idGroup != false ? "group-".$idGroup.".html" : "index.php");
    
  // Check if already in group
  $sql = $db->query("INSERT INTO groups_comparos (`group`, comparo, added) VALUES ('{$group['id']}', '{$comparo['id']}', NOW()) "
                      ."ON DUPLICATE KEY UPDATE added = NOW()");
                      
  if ( $sql ) {
    header("location:group-".$idGroup.".html");
    exit;
  }
  
  show_error("Une erreur s'est produite pendant l'ajout du Comparo.", $idGroup != false ? "group-".$idGroup.".html" : "index.php");
}

elseif ( isset($_POST['createGroup']) ) {
  $name = isset($_POST['groupName']) ? trim($_POST['groupName']) : "";
  
  if ( $name == "" ) 
    show_error("Le nom de groupe est invalide.");
    
  do {
    $rand = rand_str(10, true);
    $sql = $db->query("SELECT id FROM groups WHERE id='{$rand}'");
  } while ( mysql_num_rows($sql) > 0 );
  
  $db->insert("groups", array("id" => $rand, "name" => $name, "public_key" => get_key()));
  
  header("location:group-".$rand.".html");
  exit;
}