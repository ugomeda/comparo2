<?php

function check_color($value, $default) {
  if ( preg_match("#^\#[0-9abcdef]{6}$#i", trim($value)) ) return trim($value);
  return $default;
}

function check_int($value, $false) {
  if ( ctype_digit(trim($value)) ) return trim($value);
  return $false;
}

function get_post_val($val, $default = false, $check = false, $index = false) {
  return get_val($val, $default, $check, $index, $_POST);
}

function get_get_val($val, $default = false, $check = false, $index = false) {
  return get_val($val, $default, $check, $index, $_GET);
}

function get_val($val, $default = false, $check = false, $index = false, $from) {
  $value = $default;
  if ( $index !== false && isset($from[$val][$index]) && ( is_string($from[$val][$index]) || is_int($from[$val][$index]) ) ) {
    $value = $from[$val][$index];
  }
  elseif ( $index === false && isset($from[$val]) && ( is_string($from[$val]) || is_int($from[$val]) ) ) {
    $value = $from[$val];
  }
  
  // Check
  if ( $check == "color" ) return check_color($value, $default);
  if ( $check == "int" ) return intval($value);
  if ( $check === false ) return $value;
  
  echo "ERREUR : Type de validation invalide";
  return $default;
}


function set_in_range($val, $min, $max, $default) {
  $val = intval($val);
  if ( $val < $min || $val > $max ) {
    $val = $default;
  }
  return $val;
}