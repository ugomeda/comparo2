<?php

include_once 'includes.php';
include_once 'inc/key.php';

error_reporting(0);
if ( isset($_POST['action']) && $_POST['action'] == "set" ) {
  $privateKey = isset($_POST['privateKey']) ? filter($_POST['privateKey']) : -1;
  $comparo = isset($_POST['comparo']) ? filter($_POST['comparo']) : -1;
  $index = isset($_POST['index']) ? intval($_POST['index']) : -1;
  $value = isset($_POST['value']) ? mysql_real_escape_string(trim($_POST['value'])) : "";
    
  // Vérification clé
  $idComparo = check_key($comparo, $privateKey);
    
  if ( $idComparo !== false && $idComparo !== null ) {
    if ( $value == "" ) {
      $sql = $db->query("DELETE FROM commentaires WHERE `index` = '{$index}' AND comparo = '{$idComparo}' LIMIT 1");
      if ( $sql ) { echo "ok"; exit; }
      else { echo "Erreur lors de l'enregistrement du commentaire"; exit; }
    }
    else {   
      $i = $db->query("INSERT INTO commentaires (comparo, `index`, text, heure) VALUES ('{$idComparo}', '{$index}', '{$value}', '".date('c')."') "
                      ."ON DUPLICATE KEY UPDATE text = '{$value}', heure = '".date('c')."'");
      if ( $i ) { echo "ok"; exit; }
      else { echo "Erreur lors de l'enregistrement du commentaire"; exit; }
    }
  }
  else {
    echo "Vous n'avez pas les droits pour ajouter un commentaire sur ce comparo"; exit;
  }
}

elseif ( isset($_POST['action']) && $_POST['action'] == "discuss" ) {
  $comparo = isset($_POST['comparo']) ? filter($_POST['comparo']) : -1;
  $index = isset($_POST['index']) ? intval($_POST['index']) : -1;
  $value = isset($_POST['value']) ? trim($_POST['value']) : "";
  $pseudo = isset($_POST['pseudo']) ? trim($_POST['pseudo']) : "";
    
  $sql = $db->query("SELECT id FROM comparos WHERE id = '{$comparo}' AND discuss = '1'");
  if ( mysql_num_rows($sql) ) {
    $i = $db->insert("discuss", array("comparo" => $comparo, "ind" => $index, "value" => $value, "pseudo" => $pseudo, "heure" => date('c')));
    if ( $i ) { echo $db->insertid(); exit; }
    else { echo "Erreur lors de l'enregistrement du commentaire"; exit; }
  }
  else {
    echo "Il est impossible de discuter sur ce comparo"; exit;
  }
}

elseif ( isset($_POST['action']) && $_POST['action'] == "get" ) {
  $comparo = isset($_POST['comparo']) ? filter($_POST['comparo']) : -1;
  
  header("content-type: application/xml");
  echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
  echo "<data>\n";
  $sql = $db->query("SELECT `index`, text FROM commentaires WHERE comparo = '{$comparo}'");
  while ( $com = mysql_fetch_assoc($sql) ) {
    echo "<commentaire index=\"".$com['index']."\">".convert($com['text'])."</commentaire> \n";  
  }
  $sql = $db->query("SELECT id, ind, value, pseudo FROM discuss WHERE comparo = '{$comparo}' ORDER BY id ASC");
  while ( $dis = mysql_fetch_assoc($sql) ) {
    echo "<discuss id=\"{$dis['id']}\" index=\"".$dis['ind']."\" pseudo=\"".convert($dis['pseudo'])."\">".convert($dis['value'])."</discuss> \n";  
  }
  
  $sql = $db->query("SELECT idline, value FROM modified_lines WHERE comparoid = '{$comparo}' ORDER BY idline ASC");
  while ( $mod = mysql_fetch_assoc($sql) ) {
    echo "<modif idline=\"{$mod['idline']}\">".convert($mod['value'])."</modif> \n";  
  }
  
  
  echo "</data>";
  exit;
}

elseif ( isset($_POST['action']) && ( $_POST['action'] == "disable_discuss" || $_POST['action'] == "enable_discuss" )) {
  $privateKey = isset($_POST['privateKey']) ? filter($_POST['privateKey']) : -1;
  $comparo = isset($_POST['comparo']) ? filter($_POST['comparo']) : -1;
  $idComparo = check_key($comparo, $privateKey);
    
  if ( $idComparo !== false || $idComparo !== null ) {
    if ( $db->query("UPDATE comparos SET discuss = ".($_POST['action'] == "disable_discuss" ? "0" : "1" )." WHERE id = '{$comparo}' LIMIT 1") ) {
      echo "ok"; exit;
    }    
  }
  else {
    echo "Vous n'avez pas les droits pour gérer les discussions sur ce comparo"; exit;
  }
}

elseif ( isset($_POST['action']) && $_POST['action'] == "delete" ) {
  $privateKey = isset($_POST['privateKey']) ? filter($_POST['privateKey']) : -1;
  $comparo = isset($_POST['comparo']) ? filter($_POST['comparo']) : -1;
  $idComparo = check_key($comparo, $privateKey);
  $idDiscuss = isset($_POST['discussId']) ? intval($_POST['discussId']) : -1;
    
  if ( $idComparo != false || $idComparo != null ) {
    if ( $db->query("DELETE FROM discuss WHERE comparo = '{$comparo}' AND id = '{$idDiscuss}' LIMIT 1") ) {
      if ( $db->affectedrows() ) {
        echo "ok"; exit;
      }
      else {
        echo $idDiscuss; exit;
      }
    }    
  }
  else {
    echo "Vous n'avez pas les droits pour gérer les discussions sur ce comparo"; exit;
  }
}

elseif ( isset($_POST['action']) && $_POST['action'] == "modif" ) {
  $comparo = isset($_POST['comparo']) ? filter($_POST['comparo']) : -1;
  $privateKey = isset($_POST['privateKey']) ? filter($_POST['privateKey']) : -1;
  $idLine = isset($_POST['idline']) ? intval($_POST['idline']) : -1;
  $value = isset($_POST['value']) ? $db->escape($_POST['value']) : "";
  $index = isset($_POST['index']) ? intval($_POST['index']) : "Erreur : aucun index spécifié";
  
  // Vérification clé
  $idComparo = check_key($comparo, $privateKey);
  
  if ( $idComparo !== false && $idComparo !== null ) {
    $db->query("INSERT INTO modified_lines(comparoid, idline, value) VALUES('{$idComparo}', '{$idLine}', '{$value}') ON DUPLICATE KEY UPDATE value = '{$value}'");
    echo $index; exit;
  } else {
    echo "Vous n'avez pas les droits pour modifier ce comparo"; exit;
  }
}

else {
  echo "Erreur dans la requête";
}