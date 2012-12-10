<?php

// Amélioration simple : Si on a des correspondances longues, comparer les textes : on peut trouver des phrases qui sont les mêmes, et donc couper en un "timing" et un "edit + timing"

function simplifier_timings($timings, $precision) {
  global $config;
  $new_timings = array();
  foreach ( $timings as $id => $value ) {
    $new_timings[$id] = round($value/$precision);  
  }
  return $new_timings;
}

function timings_ok($timing1, $timing2, $precision) {
  global $config;
  return ( abs($timing2 - $timing1) < $precision );
}


function compare_timings($st1, $st2, $simple = false) {
  global $config, $highTol;

  if ( isset($highTol) && $highTol ) {
    $precision = $config['precision_ht'];
  }
  else {
    $precision = $config['precision'];
  }

  $lines_ok = array(1 => array(), 2 => array()); // Toutes les lignes trouvées
  $correspondances = array(1 => array(), 2 => array()); // La liste complète des correspondances
  $corr_simple = array(); // Les correspondances simple (une à une)
  
  $t1_s = simplifier_timings($st1['timing_start'], $precision);
  $t1_e = simplifier_timings($st1['timing_end'], $precision);
  $t2_s = simplifier_timings($st2['timing_start'], $precision);
  $t2_e = simplifier_timings($st2['timing_end'], $precision);
  
  $id1 = 0;
  foreach($t1_s as $id1 => $foo) {
    $ids2 = array_merge(array_keys($t2_s, $t1_s[$id1]-1), array_keys($t2_s, $t1_s[$id1]), array_keys($t2_s, $t1_s[$id1]+1));
  
    // Ligne normale
    $found = false;
    foreach ( $ids2 as $id2 ) {
      if ( ! $found
          && timings_ok($st1['timing_start'][$id1], $st2['timing_start'][$id2],  $precision)
          && timings_ok($st1['timing_end'][$id1], $st2['timing_end'][$id2], $precision) ) {
        $found = true;
        $lines_ok[1][] = $id1;
        $lines_ok[2][] = $id2;
        $corr_simple[$id1] = $id2;
      }
    }
    
    // Ligne normale, recherche en fonction du texte
    if ( !$found && !$simple ) {
      $id2_a = array_keys($st2['text'], $st1['text'][$id1]);
      $found = false;
      foreach ( $id2_a as $id2 ) {
        if ( ! $found ) {
          if ( array_search($id2, $lines_ok[2]) === false && abs($t1_s[$id1] - $t2_s[$id2]) < $precision ) {
            $found = true;
            $lines_ok[1][] = $id1;
            $lines_ok[2][] = $id2;
            $corr_simple[$id1] = $id2;
          }
        }      
      }    
    }
  }
  
  if ( count($corr_simple) < count($st1["id"]) * $config['min_correspondance_synchro'] ) return false;
  
  // Si on ne veut que des correspondances simples
  if ( $simple ) {
    $new_corr = array();
    $last_id1 = -1;
    $last_id2 = -1;
    foreach ( $corr_simple as $id1 => $id2 ) {
      if ( ($last_id1 + 1) != $id1 && ($id1 - $last_id1) == ($id2 - $last_id2) ) {
        for ( $i = 1; $i < $id1 - $last_id1; $i++ ) {
          $new_corr[$i+$last_id1] = $i+$last_id2;
        }
      }
      $new_corr[$id1] = $id2;  
      $last_id1 = $id1;
      $last_id2 = $id2;
    }
    return $new_corr;
  }

  // On comble les trous en utilisant les bords de chaque trou
  $id1_max = $id1;
  
  $id1 = -1; // Derniers ID croisés
  $id2 = -1;
  $fill = false; // On le passe à true quand on rencontre une phrase
  
  $i = 0; 
  while ( $i <= $id1_max) {
    if ( ! isset($corr_simple[$i]) ) {
      $fill = true;    
    }
    else {
      if ( $fill ) {      
        $correspondances[1][] = range($id1+1, $i-1);
        if ( $corr_simple[$i]-1 >= $id2+1 ) 
          $correspondances[2][] = range($id2+1, $corr_simple[$i]-1);
        else 
          $correspondances[2][] = array();
          
        $lines_ok[1] = array_merge($lines_ok[1], range($id1+1, $i-1));
        $lines_ok[2] = array_merge($lines_ok[2], range($id2+1, $corr_simple[$i]-1));
      }
      
      $correspondances[1][] = array($i); 
      $correspondances[2][] = array($corr_simple[$i]);
      
      $fill = false;
      $id1 = $i;
      $id2 = $corr_simple[$i];
    }
    $i++;
  }

  // $i = $id1_max + 1

  // On est arrivé au bout mais il reste du monde dans le buffer
  if ( $fill ) {
    if ( $i-$id1-2 >= 0 )
      $correspondances[1][] = range($id1+1, $i-1);
    else
      $correspondances[1][] = array();
    
    end($st2['id']);
    if ( key($st2['id']) >= $id2+1 ) 
      $correspondances[2][] = range($id2+1, key($st2['id']));
    else 
      $correspondances[2][] = array();
          
    $lines_ok[1] = array_merge($lines_ok[1], range($id1+1, $i-1));
    $lines_ok[2] = array_merge($lines_ok[2], range($id2+1, key($st2['id'])));
  }
  
  // Lignes ajoutées 
  $id2_add = array();
  foreach ( $st2['id'] as $id2 => $foo ) {
    if ( array_search($id2, $lines_ok[2]) === false ) {
      $id2_add[] = $id2;
    }
  }
  
  // Classer le tout
  $final = array();
  reset($id2_add);
  foreach($correspondances[1] as $id => $foo) {
    if ( current($id2_add) !== false && $correspondances[2][$id][0] > current($id2_add) ) {
      $final[] = array(1 => array(), 2 => array(current($id2_add)));
      next($id2_add);    
    }
    
    $final[] = array(1 => $correspondances[1][$id], 2 => $correspondances[2][$id]);  
  }  
  
  while ( current($id2_add) !== false ) {
    $final[] = array(1 => array(), 2 => array(current($id2_add)));
    next($id2_add); 
  }
  
  // CLASSER
  
  return $final;
}
