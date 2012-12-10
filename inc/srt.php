<?php

// decode_srt : décode un fichier SRT et renvoie les données sous forme de tableaux
function decode_srt($filename, $charset, $content = false) {
  if ( $filename !== false )
    $content = file_get_contents($filename)."\r\n\r\n";
  
  $exp = "([0-9]+)\r\n"; // L'ID
  $exp .= "([0-9]{2}):([0-9]{2}):([0-9]{2}),([0-9]{3}) --> ([0-9]{2}):([0-9]{2}):([0-9]{2}),([0-9]{3})\r\n"; // Timing
  $exp .= "(.*)\r\n\r\n"; // text
  
  $matches = array();
  preg_match_all("#".$exp."#ismU", $content, $matches);

  $st = array("id" => array(), "timing_start" => array(), "timing_end" => array(), "text" => array());
  foreach ( $matches[0] as $id => $foo ) {
    $timing_start = (((3600*$matches[2][$id]) + (60*$matches[3][$id]) + $matches[4][$id]).".".$matches[5][$id]);
    $timing_end = (((3600*$matches[6][$id]) + (60*$matches[7][$id]) + $matches[8][$id]).".".$matches[9][$id]);
    $st["id"][] = $matches[1][$id];
    $st["timing_start"][] = $timing_start;
    $st["timing_end"][] = $timing_end;
    
    // Charset issues
    if ( $charset == 1 )    
      $st["text"][] = iconv("ISO-8859-15", "UTF-8", $matches[10][$id]);    
    elseif ( $charset == 2 )
      $st["text"][] = $matches[10][$id];
    else
      $st["text"][] = iconv("windows-1252", "UTF-8", $matches[10][$id]);
  }
  
  array_multisort($st['timing_start'], SORT_ASC,
                  $st['timing_end'], SORT_ASC,
                  $st['id'],
                  $st['text']);  
  
  return $st;
}



function decode_scenechange($filename) {
  $data = file_get_contents($filename);
  $data = str_replace("\0", "", $data);
  $scs = array();
  $matches = array();
  preg_match_all("#([0-9]{2}):([0-9]{2}):([0-9]{2})\.([0-9]{3})#", $data, $matches);
  
  foreach ( $matches[0] as $id => $foo ) {
    $scs[] = (((3600*$matches[1][$id]) + (60*$matches[2][$id]) + $matches[3][$id]).".".$matches[4][$id]);
  }
  return $scs;
}

function punct_explode($string, $notags) {
  // Explose en un tableau suivant la ponctuation.
  // Garde aussi les tags en une seule partie

  // Explode simple
  $array1 = explode("\n", $string);
  
  // Explode avec les tags
  $array2 = array();
  // Tags {}
  foreach ( $array1 as $text ) {
    // Tags de type {/...}
    $offset = 0;
    while ( preg_match("#{\\\\[^}]+}#", $text, $tags, PREG_OFFSET_CAPTURE, $offset) ) {
      $tag = $tags[0];
      if ( $offset < $tag[1] ) {
        $array2[] = array("text", substr($text, $offset, $tag[1] - $offset));
      }
      $array2[] = array("tag", $tag[0]);
      $offset = $tag[1] + strlen($tag[0]);
    }
    if ( $offset < strlen($text) ) {
      $array2[] = array("text", substr($text, $offset));
    }
  }
  
  $array1 = array();
  // Tags <>
  foreach ( $array2 as $data ) {
    if ( $data[0] == "text" ) {
      $offset = 0;
      while ( preg_match("#<[^>]+>#", $data[1], $tags, PREG_OFFSET_CAPTURE, $offset) ) {
        $tag = $tags[0];
        if ( $offset < $tag[1] ) {
          $array1[] = array("text", substr($data[1], $offset, $tag[1] - $offset));
        }
        $array1[] = array("tag", $tag[0]);
        $offset = $tag[1] + strlen($tag[0]);
      }
      if ( $offset < strlen($data[1]) ) {
        $array1[] = array("text", substr($data[1], $offset));
      }
    }
    else {
      $array1[] = $data;
    }
  }
  
  $final = array();
  // Ponctuation
  foreach ( $array1 as $data ) {
    if ( $data[0] == "tag" ) {
      if ( ! $notags ) {
        $final[] = $data[1];
      }
    }
    else {
      $final = array_merge($final, do_punct($data[1]));
    }
  }
  return $final;
}
  
function do_punct($text) {
  $retour = array();

  $offset = 0;
  $prev_catch = 0;
  while ( $offset < strlen($text) ) {
    if ( in_array($text[$offset], array(".", " ", ",", "?", "!", "(", ")", "-")) ) {
      if ( $offset > $prev_catch ) {
        $retour[] = substr($text, $prev_catch, ( $offset - $prev_catch ));
      }
      $retour[] = $text[$offset];
      $prev_catch = $offset+1;
    }
    $offset++;
  }
  if ( $offset > $prev_catch ) {
    $retour[] = substr($text, $prev_catch, ( $offset - $prev_catch ));
  }
  
  return $retour;
}



function add_spaces($string) {
  return $string;
}
function remove_spaces($string) {
  return $string;
}

function clean_diff($string) {
  $string = str_replace("\n", "", $string);
  $string = str_replace(" ", "&nbsp;", $string);
  //return $string;

  // <del>A</del><ins>B</ins> <del>C</del><ins>D</ins> --> <del>A C</del><ins>B D</ins>
  do {
    $string = preg_replace ("#<del>([^<>]*)</del><ins>([^<>]*)</ins>&nbsp;<del>([^<>]*)</del><ins>([^<>]*)</ins>#", "<del>$1&nbsp;$3</del><ins>$2&nbsp;$4</ins>", $string, -1, $count);
  } while ( $count > 0 );

  // <del>A</del><ins>B</ins> <del>C</del> --> <del>A C<del><ins>B </ins>
  do {
    $string = preg_replace ("#<del>([^<>]*)</del><ins>([^<>]*)</ins>&nbsp;<del>([^<>]*)</del>#", "<del>$1&nbsp;$3</del><ins>$2&nbsp;</ins>", $string, -1, $count);
  } while ( $count > 0 ); 

  // <del>A</del><ins>B</ins> <ins>C</ins> --> <del>A </del><ins>B C</ins>
  do {
    $string = preg_replace ("#<del>([^<>]*)</del><ins>([^<>]*)</ins>&nbsp;<ins>([^<>]*)</ins>#", "<del>$1&nbsp;</del><ins>$2&nbsp;$3</ins>", $string, -1, $count);
  } while ( $count > 0 ); 
  
  // <ins>A</ins> <del>B</del><ins>C</ins> --> <ins>A C</ins><del> B</del>
  do {
    $string = preg_replace ("#<ins>([^<>]*)</ins>&nbsp;<del>([^<>]*)</del><ins>([^<>]*)</ins>#", "<ins>$1&nbsp;$3</ins><del>&nbsp;$2</del>", $string, -1, $count);
  } while ( $count > 0 ); 

  // <del>A</del> <del>B</del><ins>C</ins>
  do {
    $string = preg_replace ("#<del>([^<>]*)</del>&nbsp;<del>([^<>]*)</del><ins>([^<>]*)</ins>#", "<del>$1&nbsp;$2</del><ins>&nbsp;$3</ins>", $string, -1, $count);
  } while ( $count > 0 ); 

  return $string;
}


function find_sc($timing_min, $timing_max, $sc) {
  $timing_min -= 0.15;
  $timing_max += 0.15;
  
  $retour = array();
  
  foreach ( $sc as $timing ) {
    if ( $timing >= $timing_min && $timing <= $timing_max ) {
      $retour[] = $timing;
    }
  }
  
  return $retour;
}

function encoded_strlen($string) {
  return mb_strlen($string, "UTF-8");
}

function removeTags($string) {
  do {
    $string = preg_replace("#<[^<>]+>#", "", $string, -1, $count);
  } while ( $count > 0 ); 
  do {
    $string = preg_replace("#{\\\\[^}]+}#", "", $string, -1, $count);
  } while ( $count > 0 ); 
  
  return $string;
}