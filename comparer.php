<?php

include_once 'includes.php';
include_once 'inc/srt.php';
include_once 'inc/save.php';
include_once 'inc/compare.php';
include_once 'inc/vss.php';
include_once 'Text/Diff.php';
include_once 'Text/Diff/Renderer/inline.php';
include_once 'inc/key.php';

// Gestion de l'upload
$errors = array();

$charset = isset($_POST['charset']) ? intval($_POST['charset']) : 0;
if ( $charset > 2 || $charset < 0 ) $charset = 0;
$keep_ok = isset($_POST['keepOk']) ? ( $_POST['keepOk'] == 1 ) : false;
$notags = isset($_POST['noTags']) ? ( $_POST['noTags'] == 1 ) : false;
$useAPI = isset($_POST['useAPI']) ? ( $_POST['useAPI'] == 1 ) : false;
$highTol = isset($_POST['highTolerance']) ? ( $_POST['highTolerance'] == 1) : false;
// 0 -> windows-1252
// 1 -> ISO-8859-15
// 2 -> UTF-8

function check_file($fileindex, $ext, $force, $name) {
  global $config;
  
  // Check index exists
  if ( ! isset($_FILES[$fileindex])) {
    if ( $force ) {
      show_error("Aucun fichier n'a été reçu sur le serveur. "
                ."Vérifiez que la taille des fichiers que vous envoyez n'excède pas ".byteConvert($config['max_size']).".");
    }
    else {
      return false;
    }
  }
  
  // Check uploading error
  if ( ! $force && $_FILES[$fileindex]['error'] == UPLOAD_ERR_NO_FILE ) {
    return false;
  }
  if ( $_FILES[$fileindex]['error'] != UPLOAD_ERR_OK ) {
    show_error("Une erreur s'est produite pendant l'envoi du fichier ".$name.". "
              ."Vérifiez que la taille du fichier que vous envoyez n'excède pas ".byteConvert($config['max_size']).".");
  }
  
  // Check file size
  if ( $_FILES[$fileindex]['size'] > $config['max_size'] ) {
    show_error("Le fichier ".$name." dépasse la taille maximale autorisée par Comparo (".byteConvert($config['max_size']).").");
  }
  
  // Check extention
  if ( end(explode(".", $_FILES[$fileindex]['name'])) != $ext ) {
    show_error("L'extension du fichier ".$name." n'est pas autorisée par Comparo. L'extension doit être .".$ext.".");
  } 
  
  return true;
}

$file1 = check_file("subtitleOriginal", "srt", true, "de sous-titre non relu");
$file2 = check_file("subtitleEdited", "srt", true, "de sous-titre relu");
$vo = check_file("subtitleVO", "srt", false, "de sous-titre version originale");
$sc = check_file("scenechange", "scenechange", false, "scenechange");


// Décodage des fichiers et vérification du format
$st1 = decode_srt($_FILES['subtitleOriginal']['tmp_name'], $charset);
if ( count($st1['id']) == 0 )
  show_error("Impossible de décoder le sous-titre non relu. Vérifiez le format du fichier."); 

$st2 = decode_srt($_FILES['subtitleEdited']['tmp_name'], $charset);
if ( count($st2['id']) == 0 )
  show_error("Impossible de décoder le sous-titre relu. Vérifiez le format du fichier."); 
  
if ( $vo ) {
  $vo = decode_srt($_FILES['subtitleVO']['tmp_name'], $charset);
  if ( count($vo['id']) == 0 )
    show_error("Impossible de décoder la version originale. Vérifiez le format du fichier.");
}
else {
  $vo = false;
}

if ( $sc ) {
  // Decodage rapide, on peut le mettre avaant
  $sc = decode_scenechange($_FILES['scenechange']['tmp_name']);
}
else {
  $sc = false;
}

// On recherche s'il n'existe pas un comparo déjà existant sur ces sous-titres
$sha1_1 = sha1_file($_FILES['subtitleOriginal']['tmp_name']);
$sha1_2 = sha1_file($_FILES['subtitleEdited']['tmp_name']);
$sha1_vo = ( $vo ? sha1_file($_FILES['subtitleVO']['tmp_name']) : false );
$sha1_sc = ( $sc ? sha1_file($_FILES['scenechange']['tmp_name']) : false );

$idComparo = find_comparo($sha1_1, $sha1_2, $sha1_vo, $sha1_sc, $charset, $keep_ok, $notags, $highTol);

if ( $idComparo === false ) {
  // Recherche des timings et vérification que ça colle
  $timings = compare_timings($st1, $st2);
  if ( $timings === false ) {
    show_error("Les sous-titres relus et non relus ont moins de ".($config['min_correspondance_synchro']*100)."% de timings qui correspondent. La comparaison des fichiers est impossible. Si vous ne l'avez pas utilisée, essayer d'activer la haute tolérance sur les timings.");
  }
  if ( $vo ) {
    $timings_vo = compare_timings($st1, $vo, true);
  }

  // Save files
  $id_st1 = save_file($_FILES['subtitleOriginal']['tmp_name']);
  $id_st2 = save_file($_FILES['subtitleEdited']['tmp_name']);
  $id_vo = ( $vo ? save_file($_FILES['subtitleVO']['tmp_name']) : false);
  $id_sc = ( $sc ? save_file($_FILES['scenechange']['tmp_name']) : false);

  // Maintenant que tout va bien, on met toutes les données dans un tableau

  // Stats
  $stats = array("nb_lines" => count($st1['id']), "modif" => 0, "timing" => 0, "total" => 0);

  // Graphique
  $timing_max = ceil(max(max($st1['timing_end']), max($st2['timing_end'])));
  $timing_min = floor(min(min($st1['timing_start']), min($st2['timing_start'])));
  if ( $timing_min < 10 ) $timing_min = 0;
  $graphique = array(0 => array(), 1=>array());
  $nb_part = 30;
  for ( $i = 0; $i <= $nb_part; $i++ ) { $graphique[0][$i] = 0; $graphique[1][$i] = 0; }

  // Lines
  $lines = array();
  $renderer = new Text_Diff_Renderer_inline();
 
  foreach ($timings as $ids) {
    // Compare texts
    $text1 = array();
    foreach ( $ids[1] as $id1 ) {
      $text = str_replace("\n", "\n~NEW_LINE~\n", $st1['text'][$id1]);
      if ( count($text1) ) $text = "\n~NEW_ST~\n".$text;
      $text1 = array_merge($text1, punct_explode($text, $notags));
    }
    $text2 = array();
    $newtext = array();
    foreach ( $ids[2] as $id2 ) {
      $newtext[] = $st2['text'][$id2];
      $text = str_replace("\n", "\n~NEW_LINE~\n", $st2['text'][$id2]);
      if ( count($text2) ) $text = "\n~NEW_ST~\n".$text;
      $text2 = array_merge($text2, punct_explode($text, $notags));
    }
    

    $diff = new Text_Diff("auto", array($text1, $text2));
    $sameTexts = $diff->isEmpty();
    
    if ( strpos($st1['text'][$id1], "standing") && false ) {
      var_dump($text1);
      var_dump($text2);
      var_dump($renderer->render($diff));
      exit;
    }
    
    // Nombre de caracteres par ligne
    $cpl1 = array();
    $cpl2 = array();
    foreach ( $ids[1] as $id1 ) {
      $data = explode("\n", str_replace("\r", "", $st1['text'][$id1]));
      $data = array_map("removeTags", $data);
      $cpl1[] = array_map("encoded_strlen", $data);
    }
    foreach ( $ids[2] as $id2 ) {
      $data = explode("\n", str_replace("\r", "", $st2['text'][$id2]));
      $data = array_map("removeTags", $data);
      $cpl2[] = array_map("encoded_strlen", $data);
    }
    
    // Find VO
    if ( $vo ) {
      $vo_text = array();
      foreach ( $ids[1] as $id1 ) {
        if ( isset($timings_vo[$id1]) ) {
          $vo_text[] = $vo['text'][$timings_vo[$id1]];
        }
        else {
          $vo_text[] = false;
        }
      }
    }
    else {
      $vo_text = false;
    }
    
    // Compare timings
    $sameTimings = false;
    if ( count($ids[1]) == count($ids[2]) ) {
      $sameTimings = true;
      for ( $i = 0; $i < count($ids[1]); $i++) {
        if ( $st1['timing_start'][$ids[1][$i]] != $st2['timing_start'][$ids[2][$i]]
          || $st1['timing_end'][$ids[1][$i]] != $st2['timing_end'][$ids[2][$i]])
        {
          $sameTimings = false;        
        }
      }
    }
    
    // Find type
    if ( count($ids[1]) == 0 ) { $mode = "ins"; }
    elseif ( count($ids[2]) == 0 ) { $mode = "del"; }
    elseif ( $sameTimings && $sameTexts ) { $mode = "ok"; }
    elseif ( $sameTimings && ! $sameTexts ) { $mode = "edit"; }
    elseif ( ! $sameTimings && $sameTexts ) { $mode = "timing"; }
    else { $mode = "unk"; }
    
    // Timings
    $timings_s = array(1 => array(), 2 => array());
    $timings_e = array(1 => array(), 2 => array());
    foreach ( $ids[1] as $id1 ) {
      $timings_s[1][] = $st1['timing_start'][$id1];
      $timings_e[1][] = $st1['timing_end'][$id1];
    }
    foreach ( $ids[2] as $id2 ) {
      $timings_s[2][] = $st2['timing_start'][$id2];
      $timings_e[2][] = $st2['timing_end'][$id2];
    }
    
    // IDs
    $ids1 = array();
    $ids2 = array();
    foreach ( $ids[1] as $id1 ) {
      $ids1[] = $st1['id'][$id1];
    }
    foreach ( $ids[2] as $id2 ) {
      $ids2[] = $st2['id'][$id2];
    }
    
    // RS
    $rs1 = array();
    $rs2 = array();
    foreach ( $ids[1] as $id1 ) {
      $rs1[] = get_rsgroup($st1['timing_start'][$id1], $st1['timing_end'][$id1], $st1['text'][$id1]);
    }
    foreach ( $ids[2] as $id2 ) {
      $rs2[] = get_rsgroup($st2['timing_start'][$id2], $st2['timing_end'][$id2], $st2['text'][$id2]);
    }
    
    // Prepare show timings
    if ( $sameTimings || count($ids[2]) == 0 ) {
      $min = $st1['timing_start'][reset($ids[1])];
      $max = $st1['timing_end'][end($ids[1])];
      $time = round( ($max+$min) / 2 );  
    }
    elseif ( count($ids[1]) == 0 ) {
      $min = $st2['timing_start'][reset($ids[2])];
      $max = $st2['timing_end'][end($ids[2])];
      $time = round( ($max+$min) / 2 );
    }
    else {
      reset($ids[1]);
      $min = min($st2['timing_start'][reset($ids[2])], $st1['timing_start'][reset($ids[1])]);
      $max = max($st2['timing_end'][end($ids[2])], $st1['timing_end'][end($ids[1])]);
      $time = round( ($max+$min) / 2 );
    }
    
    // Scenechanges
    if ( ! $sc ) { $t_sc = array(); }
    else { $t_sc = find_sc($min, $max, $sc); }
    
    if ( $keep_ok || ! $sameTimings || ! $sameTexts ) {
      $lines[] = array("diff" => ( $sameTexts ?
                                      trim(htmlspecialchars(implode("", $text1), ENT_QUOTES, "UTF-8"))
                                      :trim(clean_diff($renderer->render($diff))) ),
                       "sameTe" => $sameTexts,
                       "sameTi" => $sameTimings,
                       "ids1" => $ids1,
                       "ids2" => $ids2,
                       "t1_s" => $timings_s[1],
                       "t1_e" => $timings_e[1],
                       "t2_s" => $timings_s[2],
                       "t2_e" => $timings_e[2],
                       "vo" => ( $vo_text ? implode(" ", $vo_text) : false),
                       "mode" => $mode,
                       "time" => $time,
                       "rs1" => $rs1,
                       "rs2" => $rs2,
                       "sc" => $t_sc,
                       "cpl" => array($cpl1, $cpl2),
                       "newengine" => true,
                       "newtext" => $newtext,
                       "version" => 2);
    }
    // Graphique
    $part = floor( ($time - $timing_min) * $nb_part / ( $timing_max - $timing_min ));
    $graphique[0][$part]++;

    if ( ! $sameTimings || ! $sameTexts ) {
      $graphique[1][$part]++;
    }

    // Stats
    if ( count($ids[1]) == 0 ) {
      $stats["modif"] += count($ids[2]);
      $stats["timing"] += count($ids[2]);
      $stats["nb_lines"] += count($ids[2]);
      $stats['total'] += count($ids[2]);
    }
    elseif ( count($ids[2]) == 0 ) {
      $stats["modif"] += count($ids[1]);
      $stats["timing"] += count($ids[1]);
      $stats['total'] += count($ids[1]);
    }
    else {
      if ( ! $sameTexts )
        $stats['modif'] += count($ids[1]);
      if ( ! $sameTimings )
        $stats['timing'] += count($ids[1]);
      if ( ! $sameTexts || ! $sameTimings )
        $stats['total'] += count($ids[1]);
    }
  }

  $diff_tot = round( $stats['total'] * 100 / $stats["nb_lines"]);

  $comparo = array("lines" => $lines, "stats" => $stats, "graph" => $graphique, "t_max" => $timing_max, "t_min" => $timing_min, "g_parts" => $nb_part);
  $idComparo = save_comparo($comparo, $id_st1, $id_st2, $id_vo, $id_sc, $charset, $keep_ok, $notags, $highTol, $diff_tot);
}

// Maintenant que le comparatif et les fichiers sont enregistrés, on crée un comparo
$comparo = create_comparo($idComparo, $_FILES['subtitleOriginal']['name'], $_FILES['subtitleEdited']['name'], ( $vo ? $_FILES['subtitleVO']['name'] : ""), ( $sc ? $_FILES['scenechange']['name'] : ""));

if ( $useAPI ) { echo "OK:".$comparo; exit; }

header("location:view-".$comparo.".html");