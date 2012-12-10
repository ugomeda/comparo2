<?php

include_once 'includes.php';
include_once 'inc/srt.php';
error_reporting(E_ALL);

// Get comparo
$comparoId = isset($_GET['comparo']) ? filter($_GET['comparo']) : "";
$sql = $db->query("SELECT c.id, c.nom_st2, f.sha1, co.charset FROM comparos c JOIN compare co ON co.id = c.comparatif JOIN files f ON f.id = co.idrelu WHERE c.id = '{$comparoId}' AND c.id != ''");
$comparo = $db->fetch_assoc($sql);

if ( ! $comparo ) die('Comparo inconnu');

// Get modifications
$sql = $db->query("SELECT idline, value FROM modified_lines WHERE comparoid = '{$comparoId}'");
$modifs = array();
while ( $modif = $db->fetch_assoc($sql) ) {
  $modifs[$modif['idline']] = $modif['value'];
}

// Open file
$content = implode('', gzfile($config['folder_uploads'].$comparo['sha1'].'.gz'));

// Decode srt
$subtitle = decode_srt(false, $comparo['charset'], $content);

// Recreating new SRT
$new = array();

function clean_nl($str) {
  return str_replace(array("\r", "\n"), array("", "\r\n"), $str);
}

function change_charset($str) {
  global $comparo;
  
  if ( $comparo['charset'] == 1 )    
    return iconv("UTF-8", "ISO-8859-15", $str);    
  if ( $comparo['charset'] == 2 )
    return $str;
  return iconv("UTF-8", "windows-1252", $str);
}


foreach ( $subtitle['id'] as $i => $id ) {
  if ( isset($modifs[$id]) ) $text = $modifs[$id];
  else $text = $subtitle['text'][$i];

  $new[] = $id; // Add ID
  $new[] = get_timetag($subtitle['timing_start'][$i])." --> ".get_timetag($subtitle['timing_end'][$i]); // Timings
  $new[] = clean_nl(change_charset($text)); // Text
  $new[] = ''; // Empty line
}
$new[] = ''; // Empty line

$fn = substr($comparo['nom_st2'], 0, -4)."-edited.srt";


header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Pragma: public"); 
header("Content-Description: File Transfer");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=\"{$fn}\"");
header("Content-Transfer-Encoding: binary");

echo implode("\r\n", $new);
