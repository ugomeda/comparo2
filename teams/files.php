<?php

include_once '../includes.php';
include_once '../inc/auth.inc.php';
include_once '../inc/form.inc.php';
include_once 'common.php';

$sub = get_sub();




$smarty->display("teams/files.tpl");