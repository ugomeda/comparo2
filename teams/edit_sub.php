<?php

include_once '../includes.php';
include_once '../inc/auth.inc.php';
include_once '../inc/form.inc.php';
include_once 'common.php';

// Team par défaut
$tid = isset($_GET['tid']) ? intval($_GET['tid']) : false;
$subtitle = array("subid" => false, "episode" => "", "season" => "", "episodename" => "", "teamid" => $tid, "status" => 1, "timing" => "");

// Ouvrir un sub
$subid = isset($_GET['subid']) ? intval($_GET['subid']) : false;
if ( $subid !== false ) {
  $sql = $db->query("SELECT s.subid, t.teamid, s.episode, s.saison as season, s.episodename, s.status, s.timing FROM subtitles s "
                    ."JOIN teams t ON t.teamid = s.teamid "
                    ."WHERE t.userid = '{$user['uid']}' AND s.subid = '{$subid}'");
  if ( $data = mysql_fetch_assoc($sql) ) {
    $subtitle = $data;
  }
  else {
    show_error("Le sous-titre que vous essayez d'éditer est inexistant ou inaccessible");
  }
}



if ( isset($_GET['action']) && $_GET['action'] == "updated" ) {
  $ok[] = "Le sous-titre a bien été mis à jour";
}


// Recherche des teams possibles
$teams = array();
$sql = $db->query("SELECT name, teamid FROM teams WHERE userid = '{$user['uid']}'");
while ( $team = mysql_fetch_assoc($sql) ) {
  $teams[$team['teamid']] = $team['name'];
}

// Check
if ( isset($_POST['createSub']) || isset($_POST['editSub']) ) {
  $subtitle['episode'] = get_post_val("episode", "");
  if ( check_int($subtitle['episode'], false) === false ) 
    $errors[] = "Le numéro de l'épisode doit être un chiffre";

  $subtitle['season'] = get_post_val("season", "");
  if ( check_int($subtitle['season'], false) === false ) 
    $errors[] = "La saison doit être un chiffre";

  $subtitle['episodename'] = get_post_val("episodename", "");
  if ( $subtitle['episodename'] == "" )
    $errors[] = "Le nom de l'épisode est invalide";
    
  $subtitle['teamid'] = get_post_val("teamid", "");
  if ( ! isset($teams[$subtitle['teamid']]) )
    $errors[] = "La team sélectionnée est invalide";
  
  $subtitle['status'] = get_post_val("status") == 1 ? 1 : 0;
  
  $subtitle['subid'] = intval(get_post_val("subid"));
  
  $subtitle['timing'] = get_post_val("timing", "");
  if ( $subtitle['timing'] != "" && ( check_int($subtitle['timing'], false) === false || $subtitle['timing'] < 1 ) ) 
    $errors[] = "Le timing doit être un entier non nul. Laissez le champ vide si vous ne voulez pas utiliser cette information."; 

  if ( isset($_POST['createSub']) && count($errors) == 0 ) {
    $db->insert("subtitles", array("teamid" => $subtitle['teamid'],
                                   "episode" => $subtitle['episode'],
                                   "saison" => $subtitle['season'],
                                   "episodename" => $subtitle['episodename'],
                                   "status" => $subtitle['status'],
                                   "created" => date('c'),
                                   "timing" => $subtitle['timing']));
    header("location:subtitles.php?tid={$subtitle['teamid']}&action=added");
    exit;
  }
  elseif ( isset($_POST['editSub']) && count($errors) == 0 ) {
    $db->update("subtitles", array("teamid" => $subtitle['teamid'],
                                   "episode" => $subtitle['episode'],
                                   "saison" => $subtitle['season'],
                                   "episodename" => $subtitle['episodename'],
                                   "status" => $subtitle['status'],
                                   "timing" => $subtitle['timing']), "subid = '{$subtitle['subid']}'");
    header("location:edit_sub.php?subid={$subtitle['subid']}&action=updated");
    exit;
  }

}


$smarty->assign("teams", convert($teams));
$smarty->assign("subtitle", convert($subtitle));
$smarty->assign("errors", $errors);
$smarty->assign("ok", $ok);
$smarty->display("teams/edit_sub.tpl");