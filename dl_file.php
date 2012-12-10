<?php

include_once 'includes.php';

$id = isset($_GET['id']) ? filter($_GET['id']) : -1;
$filename = isset($_GET['filename']) ? $_GET['filename'] : "unknown.srt";

$sql = $db->query("SELECT sha1, size FROM files WHERE id = '{$id}'");

$file = mysql_fetch_assoc($sql);

if ( $file ) {
  $file_path = $config['folder_uploads'].$file['sha1'].".gz";
  
  header("Cache-Control: no-cache, must-revalidate"); 
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
  header("Pragma: public"); 
  header("Content-Description: File Transfer");
  header("Content-Type: application/force-download");
  header("Content-Disposition: attachment; filename=\"".$filename."\"");
  header("Content-Transfer-Encoding: binary");
  header("Content-Length: ".$file['size']."");

  @readgzfile($file_path);
}
else {
  header("location:../../index.php");
}