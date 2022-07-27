<?php
/* Smarty version 4.1.1, created on 2022-07-27 03:27:14
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/register_class.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e0b0926871c3_17450675',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'c55464b79bfe233b1007f3906e635bd388d7acf5' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/register_class.tpl',
      1 => 1658892429,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl' => 1,
  ),
),false)) {
function content_62e0b0926871c3_17450675 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>クラス登録</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>クラス登録</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">トップページへ</a></div>
            <?php $_smarty_tpl->_subTemplateRender("file:/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

      <form action="" method="post">
        <dl>
          <dt>年度</dt>
          <dd>
            <?php echo $_smarty_tpl->tpl_vars['this_year']->value;?>
年度
          </dd>

          <dt>学年</dt>
          <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['grade']))) {?>
            <p class="error">* 学年を入力してください。</p>
          <?php }?>
          <dd>
            <select name="grade">
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['grades']->value, 'value', false, 'key');
$_smarty_tpl->tpl_vars['value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->do_else = false;
?>
                <?php if ($_smarty_tpl->tpl_vars['value']->value == $_smarty_tpl->tpl_vars['form']->value['grade']) {?>
                  <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
</option>
                <?php } else { ?>
                  <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
</option>
              <?php }?>
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </select>
          </dd>

          <dt>クラス</dt>
          <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['class'])) && $_smarty_tpl->tpl_vars['error']->value['class'] === 'blank') {?>
            <p class="error">* クラスを入力してください。</p>
          <?php } elseif ((isset($_smarty_tpl->tpl_vars['error']->value['class'])) && $_smarty_tpl->tpl_vars['error']->value['class'] === 'same_class') {?>
            <p class="error">* 登録済のクラスです。</p>
          <?php }?>
          <dd>
            <select name="class">
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['classes']->value, 'value', false, 'key');
$_smarty_tpl->tpl_vars['value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->do_else = false;
?>
                <?php if ($_smarty_tpl->tpl_vars['value']->value == $_smarty_tpl->tpl_vars['form']->value['class']) {?>
                  <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
" selected> <?php echo $_smarty_tpl->tpl_vars['key']->value;?>
 </option>
                <?php } else { ?>
                  <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
"> <?php echo $_smarty_tpl->tpl_vars['key']->value;?>
 </option>
                <?php }?>
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </select>
          </dd>
        </dl>
        <div>
          <input type="submit" value="登録" />
        </div>
      </form>
    </div>
  </div>


</body>

</html><?php }
}
