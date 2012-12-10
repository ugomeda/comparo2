<?php

include_once 'includes.php';
$smarty->assign("title", "Aide");

$smarty->assign("css", array("styles/comparo.css"));
$smarty->display("help.tpl");