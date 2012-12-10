<?php

include_once 'includes.php';

$js = array("js/jquery.min.js",
            "js/ui/effects.core.min.js",
            "js/ui/ui.core.min.js",
            "js/ui/effects.blind.min.js", 
            "js/jquery.cookies.js",
            "js/comparo-options.js",
            "js/comparo-menuindex.js",);

$smarty->assign("js", $js);
$smarty->assign("css", array("styles/comparo.css"));


$smarty->display("index.tpl");