<?php

include_once 'includes.php';
include_once 'inc/groups.php';


$js = array("js/jquery.min.js",
            "js/flot/jquery.flot.js",
            "js/ui/effects.core.min.js",
            "js/ui/ui.core.min.js",
            "js/ui/effects.blind.min.js", 
            "js/jquery.cookies.js",
            "js/comparo-menugroup.js",);
$smarty->assign("js", $js);

$smarty->assign("css", array("styles/comparo.css"));

// On vérifie que le groupe existe
$idGroup = isset($_GET['id']) ? filter($_GET['id']) : -1;
$sql = $db->query("SELECT id, name, public_key FROM groups WHERE id = '{$idGroup}' LIMIT 1");

$group = mysql_fetch_assoc($sql);

if ( ! $group ) {
  show_error("Groupe inconnu. Vérifiez l'adresse");
}


$group['name'] = convert($group['name']);

$smarty->assign("title", $group['name']);
$smarty->assign("group", $group);

$date = last_visit($group['id']);
$smarty->assign("date", $date);
addvisit($group['id']);

$sql = $db->query($query = "SELECT d.discuss, g.nom, co.id, co.nom_st1, co.nom_st2, co.nom_vo, co.nom_sc, ce.idnonrelu, "
                ."ce.idrelu, ce.idvo, ce.idsc, ce.stats_total, com.comment FROM groups_comparos g "
                ."JOIN comparos co ON co.id = g.comparo "
                ."JOIN compare ce ON ce.id = co.comparatif "
                ."LEFT OUTER JOIN (SELECT COUNT(*) as discuss, comparo FROM discuss ".($date ? "WHERE heure >= TIMESTAMP('".date('c', $date)."')" : "")."GROUP BY comparo) d ON d.comparo = co.id "
                ."LEFT OUTER JOIN (SELECT COUNT(*) as comment, comparo FROM commentaires ".($date ? "WHERE heure >= TIMESTAMP('".date('c', $date)."')" : "")."GROUP BY comparo) com ON com.comparo = co.id "
                ."WHERE g.group = '{$group['id']}' ORDER BY g.added DESC");

$comparos = array();
while ( $comparo = mysql_fetch_assoc($sql) ) {
  $c = array();
  $c['id'] = $comparo['id'];
  $c['originalName'] = $comparo['nom_st1'];
  $c['editedName'] = $comparo['nom_st2'];
  $c['hasVO'] = ( $comparo['idvo'] != "" );
  $c['hasSC'] = ( $comparo['idsc'] != "" );
  $c['nom'] = convert($comparo['nom']);
  $c['stats_total'] = $comparo['stats_total'];
  $c['discuss'] = intval($comparo['discuss']);
  $c['comment'] = intval($comparo['comment']);
  $comparos[] = $c;
}

$smarty->assign("comparos", $comparos);


$smarty->display("group.tpl");