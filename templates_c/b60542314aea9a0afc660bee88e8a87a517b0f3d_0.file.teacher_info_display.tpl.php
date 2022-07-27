<?php
/* Smarty version 4.1.1, created on 2022-07-27 08:39:41
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e0f9cd6ef1b2_03296136',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b60542314aea9a0afc660bee88e8a87a517b0f3d' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl',
      1 => 1658464400,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62e0f9cd6ef1b2_03296136 (Smarty_Internal_Template $_smarty_tpl) {
?><div style="text-align: left">
  <img src="../teacher_pictures/<?php echo $_smarty_tpl->tpl_vars['pic_info']->value['path'];?>
" width="100" height="100" alt="" />
  <?php echo $_smarty_tpl->tpl_vars['teacher_info']->value['last_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['teacher_info']->value['first_name'];?>
先生
</div><?php }
}
