<?php

include_once '../includes.php';
include_once '../inc/auth.inc.php';
include_once '../inc/form.inc.php';
include_once 'common.php';
include_once '../inc/markitup.bbcode-parser.php';

$action = get_post_val("action", false);
$sub = get_sub();

if ( $action == "last_posts" ) {
  header("content-type: application/xml");
  echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
  echo "<response>\n";
  $time = get_post_val("time", 0, "int");
  echo "<lastupdate>".(time()-1)."</lastupdate>\n";
  $sql = $db->query("SELECT u.username, p.post_text, p.postid, DATE_FORMAT(post_time, '%d/%m/%Y %H:%i') as date FROM posts p "
                    ."JOIN users u ON p.userid = u.uid "
                    ."WHERE p.subid = {$sub['subid']} AND post_time > FROM_UNIXTIME('{$time}') ORDER BY postid ASC");
  
  while ( $post = mysql_fetch_assoc($sql) ) {
    echo "<post date=\"".convert($post['date'])."\" id=\"{$post['postid']}\" username=\"".convert($post['username'])."\">".convert(BBCode2Html(convert($post['post_text'])))."</post>\n";
  }
  
  echo "</response>";
}