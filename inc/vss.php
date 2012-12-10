<?php

function get_rs($timing_start, $timing_end, $text) {
  // On calcule le RS
  $diff_timing = ($timing_end - $timing_start) * 1000 - 500;
  if ( $diff_timing <= 0 ) return 999;
  
  $text = str_replace("\r", "", $text);
  $text = str_replace("\n", "\n\r", $text);
  $text = preg_replace("#\{\\\.*\}#U", "", $text);
  $text = strip_tags($text);
  
  return 1000 * mb_strlen($text, "UTF-8") / ( $diff_timing);
}

// ANCIEN
// 1 -> "TOO SLOW!";
// 2 -> "Slow, acceptable."
// 3 -> "A bit slow.";
// 4 -> "Good.";
// 5 -> "Perfect.";
// 6 -> "Good.";
// 7 -> "A bit fast.";
// 8 -> "Fast, acceptable.";
// 9 -> "TOO FAST!";

// Nouveau
// 8 -> "TOO SLOW!";
// 6 -> "Slow, acceptable."
// 4 -> "A bit slow.";
// 2 -> "Good.";
// 1 -> "Perfect.";
// 3 -> "Good.";
// 5 -> "A bit fast.";
// 7 -> "Fast, acceptable.";
// 9 -> "TOO FAST!";


function rs_to_rsgroup($rs) {
  if ($rs < 5) return 8;
  elseif ($rs < 10) return 6;
  elseif ($rs < 13) return 4;
  elseif ($rs < 15) return 2;
  elseif ($rs < 23) return 1;
  elseif ($rs < 27) return 3;
  elseif ($rs < 31) return 5;
  elseif ($rs < 35) return 7;
  else return 9;
}

function get_rsgroup($timing_start, $timing_end, $text) {
  $rs = get_rs($timing_start, $timing_end, $text);
  return rs_to_rsgroup($rs);
}

function rsgroup_to_text($rsgroup) {
  switch($rsgroup) {
    case 8: return "TOO SLOW";
    case 6: return "Slow, acceptable";
    case 4: return "A bit slow";
    case 2: return "Good";
    case 1: return "Perfect";
    case 3: return "Good";
    case 5: return "A bit fast";
    case 7: return "Fast, acceptable";
    case 9: return "TOO FAST";
  }

  return false;
}