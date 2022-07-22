<?php
/* Smarty version 4.1.1, created on 2022-07-22 07:32:10
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/home.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62da527a8ed486_41433767',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c7ac0f9a5896dc27f56f3db29b17a22a211aa0fc' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/home.tpl',
      1 => 1658475129,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl' => 1,
  ),
),false)) {
function content_62da527a8ed486_41433767 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>教員トップページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員トップページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="search_student.php">生徒検索</a></div>
      <div style="text-align: right"><a href="register_class.php">クラス登録</a></div>
      <div style="text-align: right"><a href="create_submission.php">提出物登録</a></div>
            <?php $_smarty_tpl->_subTemplateRender("file:/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

      <div>
        <div class="box">
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['classes_array']->value[1], 'a');
$_smarty_tpl->tpl_vars['a']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['a']->value) {
$_smarty_tpl->tpl_vars['a']->do_else = false;
?>
            <div class="box">
              <a href="index_submission.php?class_id=<?php echo $_smarty_tpl->tpl_vars['a']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['a']->value['grade'];?>
-<?php echo $_smarty_tpl->tpl_vars['a']->value['class'];?>
</a>
            </div>
          <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </div>

        <div class="box">
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['classes_array']->value[2], 'a');
$_smarty_tpl->tpl_vars['a']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['a']->value) {
$_smarty_tpl->tpl_vars['a']->do_else = false;
?>
            <div class="box">
              <a href="index_submission.php?class_id=<?php echo $_smarty_tpl->tpl_vars['a']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['a']->value['grade'];?>
-<?php echo $_smarty_tpl->tpl_vars['a']->value['class'];?>
</a>
            </div>
          <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </div>

        <div class="box">
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['classes_array']->value[3], 'a');
$_smarty_tpl->tpl_vars['a']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['a']->value) {
$_smarty_tpl->tpl_vars['a']->do_else = false;
?>
            <div class="box">
              <a href="index_submission.php?class_id=<?php echo $_smarty_tpl->tpl_vars['a']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['a']->value['grade'];?>
-<?php echo $_smarty_tpl->tpl_vars['a']->value['class'];?>
</a>
            </div>
          <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </div>
      </div>


</body>

</html><?php }
}
