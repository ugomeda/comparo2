<?php

function compress($srcName, $dstName)
{
  $fp = fopen($srcName, "r");
  $data = fread ($fp, filesize($srcName));
  fclose($fp);

  $zp = gzopen($dstName, "w9");
  gzwrite($zp, $data);
  gzclose($zp);
}

function uncompress($srcName, $dstName) {
  $string = implode("", gzfile($srcName));
  $fp = fopen($dstName, "w");
  fwrite($fp, $string, strlen($string));
  fclose($fp);
} 

function save_file($src) {
  global $db, $config;
  $sha1 = sha1_file($src);
  $size = filesize($src);
  if ( ! file_exists($config['folder_uploads'].$sha1.'.gz') )
    compress($src, $config['folder_uploads'].$sha1.'.gz');
    
  $sql = $db->query("SELECT id FROM files WHERE sha1 = '{$sha1}'");
  
  if ( mysql_num_rows($sql) > 0 ) {
    return mysql_result($sql, 0);
  }
  
  do {
    $rand = rand_str(10, true);
    $sql2 = $db->query("SELECT id FROM files WHERE id = '{$rand}' LIMIT 1");
  } while ( mysql_num_rows($sql2) > 0 );
  $db->query("INSERT INTO files (id, sha1, size) VALUES ('{$rand}', '{$sha1}', '{$size}')");
    
  return $rand;
}

function save_comparo($data, $id_1, $id_2, $id_vo, $id_sc, $charset, $keep = true, $notags = false, $highTol = false, $diff_tot = 0) {
  global $db, $config;
  
  $keep = $keep ? 1 : 0;
  $tags = $notags ? 0 : 1;
  $highTol = $highTol ? 0 : 1;

  do {
    $fn = rand_str(10, false).".gz";
  } while ( file_exists($config['folder_comparos'].$fn) );
  $file = gzopen($config['folder_comparos'].$fn, "w");
  gzwrite($file, serialize($data));
  gzclose($file);
  
  $db->insert("compare", array("idnonrelu" => $id_1,
                                "idrelu" => $id_2,
                                "idvo" => ( $id_vo !== false ? $id_vo : ""),
                                "idsc" => ( $id_sc !== false ? $id_sc : ""),
                                "charset" => $charset,
                                "file" => $fn,
                                "keep" => $keep,
                                "tags" => $tags,
                                "highTolerance" => $highTol,
                                "stats_total" => intval($diff_tot)));
  return $db->insertid();
}

function find_comparo($sha1_1, $sha1_2, $sha1_vo, $sha1_sc, $charset, $keep = true, $notags = false, $highTol = false) {
  global $db, $config;
  
  $keep = $keep ? 1 : 0;
  $tags = $notags ? 0 : 1;
  $highTol = $highTol ? 0 : 1;
  
  if ( $config['debug'] ) return false;
  
  $sql = $db->query("SELECT c.id FROM compare c "
                    ."LEFT JOIN files f1 ON c.idnonrelu = f1.id "
                    ."LEFT JOIN files f2 ON c.idrelu = f2.id "
                    ."LEFT OUTER JOIN files f3 ON c.idvo = f3.id "
                    ."LEFT OUTER JOIN files f4 ON c.idsc = f4.id "
                    ."WHERE c.charset = '{$charset}' AND f1.sha1 = '{$sha1_1}' AND f2.sha1 = '{$sha1_2}'"
                    .($sha1_vo !== false ? " AND f3.sha1 = '{$sha1_vo}'": " AND c.idvo = ''")
                    .($sha1_sc !== false ? " AND f4.sha1 = '{$sha1_sc}'": " AND c.idsc = ''")
                    ." AND c.keep = '{$keep}' and c.tags = '{$tags}' AND c.highTolerance = '{$highTol}'");

  $result = mysql_fetch_assoc($sql);
  if ( $result )
    return $result['id'];

  return false;
}

function create_comparo($idComparatif, $nom1, $nom2, $nomvo, $nomsc) {
  global $db;
  
  do {
    $rand = rand_str(10, true);
    $sql = $db->query("SELECT id FROM comparos WHERE id='{$rand}'");
  } while ( mysql_num_rows($sql) > 0 );
  
  $code = get_key();
  
  $db->insert("comparos", array("id" => $rand,
                                "comparatif" => $idComparatif,
                                "code" => $code,
                                "nom_st1" => $nom1,
                                "nom_st2" => $nom2,
                                "nom_vo" => $nomvo,
                                "nom_sc" => $nomsc,
                                "created" => date('c'),
                                "last_view" => date('c'),
                                "discuss" => 1));

  return $rand;
}