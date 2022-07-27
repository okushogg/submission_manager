<?php
/* Smarty version 4.1.1, created on 2022-07-27 08:57:19
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/home.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e0fdefb31b27_28150928',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c7ac0f9a5896dc27f56f3db29b17a22a211aa0fc' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/home.tpl',
      1 => 1658912232,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/header.tpl' => 1,
    'file:../teacher/teacher_info_display.tpl' => 1,
  ),
),false)) {
function content_62e0fdefb31b27_28150928 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="jp">

<?php $_smarty_tpl->_subTemplateRender("file:../common/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

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
            <?php $_smarty_tpl->_subTemplateRender("file:../teacher/teacher_info_display.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

      <?php if ($_smarty_tpl->tpl_vars['classes_array']->value) {?>

      <?php if ((isset($_smarty_tpl->tpl_vars['classes_array']->value[1]))) {?>
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
      <?php } else { ?>
        <li>新年度の1年生クラスは未登録です。</li>
      <?php }?>
       
       <?php if ((isset($_smarty_tpl->tpl_vars['classes_array']->value[2]))) {?>
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
        <?php } else { ?>
          <li>新年度の２年生クラスは未登録です。</li>
        <?php }?>

        <?php if ((isset($_smarty_tpl->tpl_vars['classes_array']->value[3]))) {?>
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
        <?php } else { ?>
          <li>新年度の3年生クラスは未登録です。</li>
        <?php }?>
      </div>
      <?php } else { ?>
      <p>新年度のクラスが未登録です。</p>
      <?php }?>
</body>

</html><?php }
}
