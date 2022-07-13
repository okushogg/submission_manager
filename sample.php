<?php

require_once('smarty/Smarty.class.php');

$smarty = new Smarty();

$smarty->template_dir = '/Applications/MAMP/htdocs/submission_manager/templates/';
$smarty->compile_dir  = '~/submission_manager/templates_c/';
$smarty->config_dir   = '~/submission_manager/configs/';
$smarty->cache_dir    = '~/submission_manager/cache/';

$smarty->assign('msg','Hello World!');
$smarty->display('sample.tpl');

?>