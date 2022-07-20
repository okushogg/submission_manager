<?php
/* Smarty version 4.1.1, created on 2022-07-20 07:30:11
  from '/Applications/MAMP/htdocs/submissions_manager/templates/student/log_in.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62d7af03428d81_84538294',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7772d1fafec24cd0200a61eb3e225206c245db62' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/student/log_in.tpl',
      1 => 1658302208,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62d7af03428d81_84538294 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="../style.css" />
  <title>生徒ログインページ</title>
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒ログインページ</h1>
    </div>
    <div id="content">
      <div id="lead">
        <p>メールアドレスとパスワードを記入してログインしてください。</p>
        <p>登録がまだの方はこちらからどうぞ。</p>
        <p>&raquo;<a href="sign_up.php">登録手続きをする</a></p>
        <p>パスワードをお忘れの方はこちら。</p>
        <p>&raquo;<a href="reset_password.php">パスワードの再設定</a></p>
      </div>
      <form action="" method="post">
        <dl>
          <dt>メールアドレス</dt>
          <dd>
            <input type="text" name="email" size="35" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['email']->value;?>
" />
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['login'])) && $_smarty_tpl->tpl_vars['error']->value['login'] === 'blank') {?>
              <p class="error">* メールアドレスとパスワードをご記入ください</p>
            <?php } elseif ((isset($_smarty_tpl->tpl_vars['error']->value['login'])) && $_smarty_tpl->tpl_vars['error']->value['login'] === 'failed') {?>
              <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
            <?php }?>
          </dd>
          <dt>パスワード</dt>
          <dd>
            <input type="password" name="password" size="35" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['password']->value;?>
" />
          </dd>
        </dl>
        <div>
          <input type="submit" value="ログインする" />
        </div>
      </form>
      <div style="text-align: right; margin: 10px;"><a href="../teacher/log_in.php">>教員用ログインページ</a></div>

    </div>
  </div>
</body>

</html><?php }
}
