<?php

$config = array(
  'mysql_server' => 'localhost',
  'mysql_pass' => 'pass',
  'mysql_user' => 'user',
  'mysql_db' => 'comparo',
  'max_size' => 262144, // Taille maximale d'un srt en octets, ici 256ko
  'min_correspondance_synchro' => 0.3,
  'precision' => 0.2,
  'precision_ht' => 1,
  'debug' => false,
  'folder_uploads' => '../files/uploads/',
  'folder_comparos' => '../files/comparos/',
  'grain_de_sel' => '', // Permet d'éviter les vols de mots de passe si les MD5 sont volés (vol de base de données)
  'cookie_path' => '/'
);
