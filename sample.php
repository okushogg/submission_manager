<?php

require('smarty/Smarty.class.php');

$smarty = new Smarty();

$smarty->template_dir = 'templates/';
$smarty->compile_dir  = 'templates_c/';

$smarty->assign('msg', 'Fucking stuck');
$smarty->display('sample.tpl');
?>