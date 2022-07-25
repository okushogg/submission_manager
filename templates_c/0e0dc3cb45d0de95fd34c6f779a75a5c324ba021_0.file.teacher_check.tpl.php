<?php
/* Smarty version 4.1.1, created on 2022-07-25 06:22:32
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_check.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62de36a80d1e20_41282347',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0e0dc3cb45d0de95fd34c6f779a75a5c324ba021' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_check.tpl',
      1 => 1658730147,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62de36a80d1e20_41282347 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="../style.css" />
  <title>確認ページ</title>
  <?php echo '<script'; ?>
 type="text/javascript" src="../private/js/teacher_page.js"><?php echo '</script'; ?>
>
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>確認ページ</h1>
    </div>
    <div id="content">
      <div id="lead">
       <a href="javascript:history.back()">前に戻る</a>
      </div>
      <form action="" method="post">
        <dl>
          <dt>パスワード</dt>
          <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['teacher_check']))) {?>
            <p class="error">* 正しいパスワードを入力してください。</p>
          <?php }?>
          <dd>
            <input type="password" name="password" size="35" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['password']->value;?>
" />
          </dd>
        </dl>
        <div>
          <input type="submit" value="教員ページへ" />
        </div>
      </form>
    </div>
  </div>
</body>

</html><?php }
}
