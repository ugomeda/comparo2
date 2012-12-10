<?php

// On cherche à partir d'un temps en secondes, à obtenir le tag type 00:00:02,200
function get_timetag($time) {
  // Le chiffre après la virgule
  $mil = round(($time - floor($time)) * 1000);
  // Le chiffre des heures
  $hou = floor($time / 3600);
  // Et les minutes
  $min = floor(($time - ($hou*3600)) / 60);
   // Et les secondes
  $sec = floor($time - ($hou*3600) - ($min*60));
  
  $mil = str_pad(strval($mil), 3, "0", STR_PAD_LEFT);
  $hou = str_pad(strval($hou), 2, "0", STR_PAD_LEFT); 
  $min = str_pad(strval($min), 2, "0", STR_PAD_LEFT); 
  $sec = str_pad(strval($sec), 2, "0", STR_PAD_LEFT); 
  return $hou.":".$min.":".$sec.",".$mil;
}

function extract_array($st, $data) {
  $retour = array();
  foreach ( $st as $id => $line ) {
    $retour[$id] = $line[$data];
  }
  return $retour;
}

function byteConvert( $bytes ) {
    if ($bytes<=0)
        return '0 Byte';
    
    $convention=1024; //[1000->10^x|1024->2^x]
    $s=array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB');
    $e=floor(log($bytes,$convention));
    return round($bytes/pow($convention,$e),2).' '.$s[$e];
}

function rand_str($length=8,$maj=false,$addchrs='') {
  $letters = 'abcdefghijklmnopqrstuvwxyz1234567890'.$addchrs.(($maj) ? "ABCDEFGHIJKLMNOPQRSTUVWXYZ" : "");
  $number = strlen($letters)-1;
  $rand = '';
  for ($i = 0; $i<$length; $i++)
    $rand .=  $letters[mt_rand(0,$number)];

  return $rand;
}

function filter($in) {
	$search = array ('@[éèêëÊË]@i','@[àâäÂÄ]@i','@[îïÎÏ]@i','@[ûùüÛÜ]@i','@[ôöÔÖ]@i','@[ç]@i','@[ ]@i','@[^a-zA-Z0-9_]@');
	$replace = array ('e','a','i','u','o','c','_','');
	return preg_replace($search, $replace, $in);
}

// htmlentities configuré
function convert($str) {
  if ( is_array($str) ) {
    return array_map("convert", $str);
  }
  return htmlspecialchars($str, ENT_COMPAT, "UTF-8");
}

function addslashes_a($str) {
  if ( is_array($str) ) {
    return array_map("addslashes_a", $str);
  }
  return addslashes($str);
}

function save_error($string) {
  global $db;
  
  $db->insert("erreurs", array("error" => $string, "heure" => date('c')));
}