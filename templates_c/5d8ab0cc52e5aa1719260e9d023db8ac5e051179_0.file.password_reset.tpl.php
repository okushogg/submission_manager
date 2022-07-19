<?php
/* Smarty version 4.1.1, created on 2022-07-19 17:47:22
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/password_reset.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62d68bba805206_72254337',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5d8ab0cc52e5aa1719260e9d023db8ac5e051179' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/password_reset.tpl',
      1 => 1658227620,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62d68bba805206_72254337 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>教員用パスワードリセット</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員用パスワードリセット</h1>
    </div>
    <div id="content">
      <div id="lead">
        <p>&raquo;<a href="log_in.php">ログインページ</a></p>
      </div>
      <p>パスワードリセット用のリンクをメールにてお送りします。</p>
      <p>ご登録済のメールアドレスをご入力ください。</p>
      <form action="" method="post">
        <dl>
          <dt>メールアドレス</dt>
          <dd>
            <input type="text" name="email" size="35" maxlength="255" value="" />
          </dd>
          <input type="submit" value="送信" />
    </div>
    </form>
  </div>
  </div>
</body>

</html>

</html><?php }
}
