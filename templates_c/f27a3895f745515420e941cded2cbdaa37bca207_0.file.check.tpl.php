<?php
/* Smarty version 4.1.1, created on 2022-07-27 06:35:48
  from '/Applications/MAMP/htdocs/submissions_manager/templates/student/check.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62e0dcc4d946d1_12698095',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f27a3895f745515420e941cded2cbdaa37bca207' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/student/check.tpl',
      1 => 1658903746,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62e0dcc4d946d1_12698095 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>生徒登録確認</title>

  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒登録確認</h1>
    </div>

    <div id="content">
      <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
      <form action="" method="post">
        <dl>
          <dt>氏</dt>
          <dd><?php echo $_smarty_tpl->tpl_vars['form']->value['last_name'];?>
</dd>
          <dt>名</dt>
          <dd><?php echo $_smarty_tpl->tpl_vars['form']->value['first_name'];?>
</dd>
          <dt>性別</dt>
          <dd><?php echo display_sex($_smarty_tpl->tpl_vars['form']->value['sex']);?>
</dd>
          <dt>クラス</dt>
          <dd><?php echo '<?php'; ?>
 <?php echo $_smarty_tpl->tpl_vars['my_class']->value['grade'];?>
-<?php echo $_smarty_tpl->tpl_vars['my_class']->value['class'];?>
></dd>
          <dt>メールアドレス</dt>
          <dd><?php echo $_smarty_tpl->tpl_vars['form']->value['email'];?>
</dd>
          <dt>パスワード</dt>
          <dd>
            【表示されません】
          </dd>
          <dt>顔写真</dt>
          <dd>
            <?php if ($_smarty_tpl->tpl_vars['form']->value['image'] !== '') {?>
            <img src="../student_pictures/<?php echo $_smarty_tpl->tpl_vars['form']->value['image'];?>
" width="100" alt="" />
            <?php } else { ?>
              <img src="../student_pictures/no_image.jpg" width="100" alt="" />
            <?php }?>
          </dd>
        </dl>
        <div><a href="sign_up.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
      </form>
    </div>

  </div>
</body>

</html><?php }
}
