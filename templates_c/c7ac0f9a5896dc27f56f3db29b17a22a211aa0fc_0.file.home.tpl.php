<?php
/* Smarty version 4.1.1, created on 2022-07-19 02:43:36
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/home.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62d61a58a1cff1_04663127',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c7ac0f9a5896dc27f56f3db29b17a22a211aa0fc' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/home.tpl',
      1 => 1658198614,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62d61a58a1cff1_04663127 (Smarty_Internal_Template $_smarty_tpl) {
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
      <div style="text-align: left">
        <img src="../teacher_pictures/<?php echo $_smarty_tpl->tpl_vars['pic_info']->value['path'];?>
" width="100" height="100" alt="" />
        <?php echo (($_smarty_tpl->tpl_vars['teacher_info']->value['last_name']).($_smarty_tpl->tpl_vars['teacher_info']->value['first_name'])).("先生");?>

      </div>

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
