<?php

include_once '../includes.php';
include_once '../inc/auth.inc.php';
include_once 'common.php';
include_once '../inc/markitup.bbcode-parser.php';

$smarty->assign("css", array("styles/teams.css", "js/markitup/skins/simple/style.css", "js/markitup/sets/bbcode/style.css"));
$smarty->assign("js", array("js/jquery.min.js",
                            "js/ui/effects.core.min.js",
                            "js/ui/ui.core.min.js",
                            "js/ui/effects.blind.min.js",
                            "js/ui/effects.highlight.min.js",
                            "js/markitup/jquery.markitup.js",
                            "js/markitup/sets/bbcode/set.js",
                            "js/team/forum.js"));

$sub = get_sub();

if ( isset($_POST['send']) ) {
  $msg = get_post_val("message");
  if ( trim($msg) == "" ) {
    $errors[] = "Le message ne doit pas être vide";
  }
  else {
    $db->insert("posts", array("subid" => $sub['subid'], "userid" => $user['uid'], "post_time" => date('c'), "post_text" => $msg));
    $id = $db->insertid();
    header("location:forum.php?sid={$sub['subid']}#p-{$id}");
    exit;
  }
}

$msgs = array();
$sql = $db->query("SELECT u.username, p.post_text, p.postid, DATE_FORMAT(post_time, '%d/%m/%Y %H:%i') as date FROM posts p JOIN users u ON p.userid = u.uid WHERE p.subid = {$sub['subid']} ORDER BY postid ASC");

while ( $post = mysql_fetch_assoc($sql) ) {
  $post['html'] = BBCode2Html(convert($post['post_text']));
  $post['username'] = convert($post['username']);
  $msgs[] = $post;
}


$smarty->assign("js_head", "var lastupdate = ".(time() - 1).";\nvar sid = {$sub['subid']};");

$smarty->assign("msgs", $msgs);
$smarty->assign("errors", $errors);
$smarty->assign("ok", $ok);

$smarty->display("teams/forum.tpl");