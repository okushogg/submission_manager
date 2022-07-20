<?php
/* Smarty version 4.1.1, created on 2022-07-20 17:07:46
  from '/Applications/MAMP/htdocs/submissions_manager/templates/student/reset_password_form.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62d7d3f2b00034_81614538',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd314adf12274c4f4dff54dad6c618ea0a4c55ece' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/student/reset_password_form.tpl',
      1 => 1658311659,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62d7d3f2b00034_81614538 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>生徒用パスワードリセットフォーム</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒用パスワードリセットフォーム</h1>
    </div>
    <div id="content">
      <p>新しいパスワードをご入力ください。</p>
      <form action="" method="post">
        <dl>
          <dt>新しいパスワード</dt>
          <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['password'])) && $_smarty_tpl->tpl_vars['error']->value['password'] === 'blank') {?>
            <p class="error">* パスワードを入力してください</p>
          <?php } elseif ((isset($_smarty_tpl->tpl_vars['error']->value['password'])) && $_smarty_tpl->tpl_vars['error']->value['password'] === 'length') {?>
            <p class="error">* パスワードは4文字以上で入力してください</p>
          <?php }?>
          <dd>
            <input type="password" name="password" size="10" maxlength="20" value="<?php echo $_smarty_tpl->tpl_vars['form']->value['password'];?>
" />
          </dd>
          <input type="submit" value="送信" />
      </form>
    </div>

  </div>
</body>

</html>

</html><?php }
}
