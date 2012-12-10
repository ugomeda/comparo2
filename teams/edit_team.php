<?php

include_once '../includes.php';
include_once '../inc/auth.inc.php';
include_once '../inc/form.inc.php';
include_once 'common.php';

$smarty->assign("css", array("styles/teams.css", "styles/colorpicker/css/colorpicker.css"));
$smarty->assign("js", array("js/jquery.min.js", "js/colorpicker.js"));

// Team par défaut
$team = array("teamName" => "", "teamid" => 0, "stepColor" => array("#ffed2b", "#ff872b", "#267f00", "#ffffff", "#ffffff"), "stepName" => array("Synchronisation", "Traduction", "Relecture", "",""));

// Editer team
$tid = isset($_GET['tid']) ? intval($_GET['tid']) : false;

if ( $tid !== false ) {
  $sql = $db->query("SELECT teamid, userid, name FROM teams WHERE teamid = '{$tid}' AND userid = '{$user['uid']}'");
  $teamdata = mysql_fetch_array($sql);
  
  if ( ! $teamdata ) show_error("La team que vous souhaitez éditer n'existe pas ou est inaccessible.");
    
  else {
    $team['teamName'] = $teamdata['name'];
    $team['teamid'] = $teamdata['teamid'];
    $sql = $db->query("SELECT `index`, name, color FROM teams_steps WHERE teamid = '{$tid}'");
    $stepColor = array_fill(0, 5, "#ffffff");
    $stepName = array_fill(0, 5, "");
    while ( $step = mysql_fetch_assoc($sql) ) {
      $stepColor[$step['index']] = $step['color'];
      $stepName[$step['index']] = $step['name'];
    }
    $team['stepColor'] = $stepColor;
    $team['stepName'] = $stepName;
  }
}

if ( isset($_GET['action']) && $_GET['action'] == "edited" ) {
  $ok[] = "La team a bien été mise à jour";
}

// Gestion ajout/édition groupe
if ( isset($_POST['createTeam']) || isset($_POST['editTeam']) ) {

  // Vérification
  $team['teamName'] = get_post_val("teamName", "");
  $team['teamid'] = get_post_val("teamid");
  if ( trim(get_post_val("teamName", "")) == "" ) {
    $errors[] = "Nom de team invalide.";
  }

  $hasStep = false;
  for ( $i = 0; $i < 5; $i++ ) {
    if ( trim(get_post_val("stepName", "", false, $i)) != "" ) 
      $hasStep = true;
      
    $team['stepColor'][$i] = get_post_val("stepColor", "#ffffff", "color", $i);
    $team['stepName'][$i] = get_post_val("stepName", "", false, $i);
  }
  
  if ( ! $hasStep )
    $errors[] = "Vous devez spécifier au moins une étape";
    
  if ( count($errors) == 0 ) {
    if ( isset($_POST['createTeam']) ) {
      $db->insert("teams", array("userid" => $user['uid'], "name" => $team['teamName']));
      $teamid = $db->insertid();
      for ( $i = 0; $i < 5; $i++ ) {
        if ( $team['stepName'][$i] != "" ) 
          $db->insert("teams_steps", array("teamid" => $teamid, "`index`" => $i, "name" => $team['stepName'][$i], "color" => $team['stepColor'][$i]));
      }
      
      header("location:myteams.php?action=added");
      exit;
    }
    else {
      $sql = $db->query("SELECT teamid FROM teams WHERE teamid = '".intval($team['teamid'])."' AND userid = '{$user['uid']}'");
      if ( mysql_num_rows($sql) > 0 ) {
        $sql = $db->query($query = "UPDATE teams SET name = '".mysql_real_escape_string($team['teamName'])."' WHERE teamid = '".intval($team['teamid'])."'");
        $sql = $db->query("DELETE FROM teams_steps WHERE teamid = '".intval($team['teamid'])."'");
        for ( $i = 0; $i < 5; $i++ ) {
          if ( $team['stepName'][$i] != "" ) 
            $db->insert("teams_steps", array("teamid" => intval($team['teamid']), "`index`" => $i, "name" => $team['stepName'][$i], "color" => $team['stepColor'][$i]));
        }
      }
      header("location:edit_team.php?tid=".intval($team['teamid'])."&action=edited");
      exit;
    }
  }
}

$smarty->assign("team", convert($team));
$smarty->assign("errors", $errors);
$smarty->assign("ok", $ok);
$smarty->display("teams/edit_team.tpl");