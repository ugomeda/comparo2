<?php

include_once "includes.php";
include_once "inc/utils.php";
include_once "inc/vss.php";

// Initialisation template
$js = array("js/jquery.min.js",
            "js/flot/jquery.flot.js",
            "js/ui/effects.core.min.js",
            "js/ui/ui.core.min.js",
            "js/ui/effects.highlight.min.js",
            "js/ui/effects.blind.min.js", 
            "js/jquery.cookies.js",
            "js/jtip.js",
            "js/comparo-pref.js",
            "js/comparo-menu.js",
            "js/comparo-comment.js",
            "js/thickbox.js",
            "js/comparo-copier.js",
            "js/comparo-details.js",
            "js/comparo.js");
$smarty->assign("js", $js);
$js_ie = array("js/flot/excanvas.pack.js");
$smarty->assign("js_ie", $js_ie);
$smarty->assign("css", array("styles/comparo.css"));

// On cherche le comparo à partir de l'ID en Get
$idComparo = isset($_GET['id']) ? filter($_GET['id']) : -1;
$sql = $db->query("SELECT c.id, c.nom_st1, c.nom_st2, c.nom_vo, c.nom_sc, cf.idsc, cf.file, cf.idnonrelu, cf.idrelu, cf.idvo, c.code, c.discuss, cf.keep, cf.tags FROM comparos c "
                  ."JOIN compare cf ON cf.id = c.comparatif "
                  ."WHERE c.id = '{$idComparo}'");
$comparo = mysql_fetch_assoc($sql);

// Si inexistants
if ( ! $comparo ) {
  show_error("Comparo inexistant.");
} 

// Si valide
$smarty->assign("comparo", $comparo);
$smarty->assign("title", $comparo['nom_st2']);

// On met à jour la date
$db->query("UPDATE comparos SET last_view = '".date('Y-m-d H:i:s')."' WHERE id = '{$comparo['id']}' LIMIT 1");

// Ouverture comparo compressé
$content = unserialize(implode("", gzfile($config['folder_comparos'].$comparo['file'].'.gz')));
$smarty->assign("t_max", $content['t_max']);
$smarty->assign("keep_ok", ($comparo['keep'] == 1));
$smarty->assign("keep_tags", ($comparo['tags'] == 1));

// Stats
$stats = array("timing" => round($content['stats']['timing'] * 100 / $content['stats']['nb_lines']),
               "text" => round($content['stats']['modif'] * 100 / $content['stats']['nb_lines']),
               "total" => round($content['stats']['total'] * 100 / $content['stats']['nb_lines']));
$smarty->assign("stats", $stats);

// Liste des times et id sur la source et le relu, et des sc
$times = array();
$timings_nonrelu_s = array();
$timings_nonrelu_e = array();
$timings_relu_s = array();
$timings_relu_e = array();
$lines_modified = array();
$ids_s = array();
$ids_r = array();
$sc = array();

// Lines
$lines = $content['lines'];
foreach ( $lines as $id => $line ) {

  // Display text
  if ( isset($line['newengine']) ) {
    $lines[$id]['text'] = str_replace(array("~NEW_ST~", "~NEW_LINE~"), array(" ][ ", " || "), $line['diff']);
  }
  else {
    $lines[$id]['text'] = str_replace(array("\n~NEW_ST~", "\n", "~NEW_ST~"), array(" ][ ", " || ", " ][ "), $line['diff']);
  }
  
  // Edit mod
  if ( $line['version'] >= 2 ) {
    $lines_modified[] =  str_replace(array("\n", "\r"), array("\\n", ''), '["'.implode('","', addslashes_a($line['newtext'])).'"]');
  }
  else {
    $lines_modified[] = "[]";
  }
  
  
  // Time
  $times[] = $line['time'];
  
  // Prepare show timings
  if ( $line['sameTi'] || count($line['t2_s']) == 0 ) {
    $timing = get_timetag(reset($line['t1_s']));
    $timing .= " --> ";
    $timing .= get_timetag(end($line['t1_e']));
  }
  elseif ( count($line['t1_s']) == 0 ) {
    $timing = get_timetag(reset($line['t2_s']));
    $timing .= " --> ";
    $timing .= get_timetag(end($line['t2_e']));
  }
  else {
    $t1_s = reset($line['t1_s']);
    $t1_e = end($line['t1_e']);
    $t2_s = reset($line['t2_s']);
    $t2_e = end($line['t2_e']);
    $decalage_sta = $t2_s - $t1_s;
    $decalage_end = $t2_e - $t1_e; 
    $aff_decal_sta = ( $decalage_sta > 0 ) ? "+".number_format($decalage_sta, 3) : number_format($decalage_sta, 3);
    $aff_decal_end = ( $decalage_end > 0 ) ? "+".number_format($decalage_end, 3) : number_format($decalage_end, 3);
    if ( $aff_decal_sta == "0.000" ) { $aff_decal_sta = "="; }
    if ( $aff_decal_end == "0.000" ) { $aff_decal_end = "="; }
    $timing = "[".$aff_decal_sta."] ".get_timetag($t2_s)." --> ".get_timetag($t2_e)." [".$aff_decal_end."]";
  }
  $lines[$id]['timing'] = $timing; 
  
  // RS
  if ( isset($line['rs1']) && count($line['rs1']) && count($line['rs2']) ) {
    $rs1 = max($line['rs1']);
    if ( $rs1 == 6 ) $rs1 = 4;
    $rs2 = max($line['rs2']);
    if ( $rs2 == 6 ) $rs2 = 4;
    
    
    if ( $rs1 != $rs2 && isset($line['version']) && $line['version'] >= 1) {
      $lines[$id]['rs1_display'] = array('class' => 'rs_'+$rs1, 'text' => rsgroup_to_text($rs1));
      $lines[$id]['rs2_display'] = array('class' => 'rs_'+$rs2, 'text' => rsgroup_to_text($rs2));
    }  
  }
  
  // Scene change
  if ( isset($line['sc']) ) $sc[] = "[".implode(",",$line['sc'])."]";
  else $sc[] = "[]";
  
  // Details of timings and ids
  if ( isset($line['ids1']) ) {
    $timings_nonrelu_s[] = "[".implode(",", $line['t1_s'])."]";
    $timings_nonrelu_e[] = "[".implode(",", $line['t1_e'])."]";
    $timings_relu_s[] = "[".implode(",", $line['t2_s'])."]";
    $timings_relu_e[] = "[".implode(",", $line['t2_e'])."]";
    $ids_s[] = "[".implode(",",$line['ids1'])."]";
    $ids_r[] = "[".implode(",",$line['ids2'])."]";
  }
  else {
    $timings_nonrelu_s[] = "[]";
    $timings_nonrelu_e[] = "[]";;
    $timings_relu_s[] = "[]";
    $timings_relu_e[] = "[]";
    $ids_s[] = "[]";
    $ids_r[] = "[]";
  }
  
  
  
  if ( isset($line['ids1']) ) {
    // Inline
    if ( count($line['ids1']) > 1 )
      $lines[$id]['ids_nonrelu'] = reset($line['ids1'])." - ".end($line['ids1']);
    else
      $lines[$id]['ids_nonrelu'] = reset($line['ids1']);
      
    if ( count($line['ids2']) > 1 )
      $lines[$id]['ids_relu'] = reset($line['ids2'])." - ".end($line['ids2']);
    else
      $lines[$id]['ids_relu'] = reset($line['ids2']);
  }
  else {
    $lines[$id]['ids_relu'] = false;
    $lines[$id]['ids_nonrelu'] = false;
  }
  
  // CPL
  if ( isset($line['cpl']) ) {
    $cpl1 = array();
    $cpl1_max = 0;
    foreach ( $line['cpl'][0] as $cpl ) {
      $cpl1[] = implode(" ", $cpl);
      $cpl1_max = max($cpl1_max, max($cpl));
    }
    $lines[$id]['cpl1'] = implode(" | ", $cpl1);
    $lines[$id]['cpl1_max'] = $cpl1_max;
    
    $cpl2 = array();
    $cpl2_max = 0;
    foreach ( $line['cpl'][1] as $cpl ) {
      $cpl2[] = implode(" ", $cpl);
      $cpl2_max = max($cpl2_max, max($cpl));
    }
    $lines[$id]['cpl2'] = implode(" | ", $cpl2);
    $lines[$id]['cpl2_max'] = $cpl2_max;
  }
  
  
  
}
$smarty->assign("lines", $lines);

// Graphique
$graph_modif = array("[".$content['t_min'].", 0]");
foreach ( $content['graph'][0] as $part => $value ) {
  if ( $part < $content['g_parts']) {
    $graph_modif[] = "[".($content['t_min'] + round( (($part + 0.5) / $content['g_parts']) * ($content['t_max']-$content['t_min']))).", ".$value."]";
  }
}
$graph_modif[] = "[".$content['t_max'].", 0]";

$graph_normal = array("[".$content['t_min'].", 0]");
foreach ( $content['graph'][1] as $part => $value ) {
  if ( $part < $content['g_parts']) {
    $graph_normal[] = "[".($content['t_min'] + round( (($part + 0.5) / $content['g_parts']) * ($content['t_max']-$content['t_min']))).", ".$value."]";
  }
}
$graph_normal[] = "[".$content['t_max'].", 0]";

$smarty->assign("js_head", "var idComparo = '{$comparo['id']}';\n"
                          ."var publicKey = '{$comparo['code']}';\n"
                          ."var discussAllowed = ".($comparo['discuss'] == 1 ? "true" : "false").";\n"
                          ."var times = [".implode(",", $times)."];\n"
                          ."var ids_s = [".implode(",", $ids_s)."];\n"
                          ."var ids_r = [".implode(",", $ids_r)."];\n"
                          ."var timing_s_s = [".implode(",", $timings_nonrelu_s)."];\n"
                          ."var timing_s_e = [".implode(",", $timings_nonrelu_e)."];\n"
                          ."var timing_r_s = [".implode(",", $timings_relu_s)."];\n"
                          ."var timing_r_e = [".implode(",", $timings_relu_e)."];\n"
                          ."var sc = [".implode(",", $sc)."];\n"
                          ."var graph_normal = [".implode(", ", $graph_normal)."];\n"
                          ."var graph_modif = [".implode(", ", $graph_modif)."];\n"
                          ."var lines_modified = [".implode(",", $lines_modified)."];\n");

$smarty->display('view.tpl');