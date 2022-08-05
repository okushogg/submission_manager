<?php

require('smarty/Smarty.class.php');

class Smarty_submission_manager extends Smarty {

   function __construct()
   {
        parent::__construct();

        $this->template_dir = '/Applications/MAMP/htdocs/submissions_manager/templates';
        $this->compile_dir  = '/Applications/MAMP/htdocs/submissions_manager/templates_c/';
        $this->config_dir = '/Applications/MAMP/htdocs/submissions_manager/configs/';

        $this->caching = Smarty::CACHING_LIFETIME_CURRENT;
        $this->assign('app_name', 'submission_manager');
   }
}
?>