<?php
/* Smarty version 4.1.1, created on 2022-08-08 12:13:29
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/check.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62f0fde946ef14_19419572',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1e5f2e90b07381708786d99300804c27700f3726' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/check.tpl',
      1 => 1659670486,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/header.tpl' => 1,
  ),
),false)) {
function content_62f0fde946ef14_19419572 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="ja">

<?php $_smarty_tpl->_subTemplateRender("file:../common/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員登録確認</h1>
    </div>

    <div id="content">
      <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
      <form action="" method="post">
        <dl>
          <dt>姓</dt>
          <dd><?php echo $_smarty_tpl->tpl_vars['form']->value['last_name'];?>
</dd>
          <dt>名</dt>
          <dd><?php echo $_smarty_tpl->tpl_vars['form']->value['first_name'];?>
</dd>
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
              <img src="../teacher_pictures/<?php echo $_smarty_tpl->tpl_vars['form']->value['image'];?>
" width="100" alt="" />
            <?php } else { ?>
              <img src="../teacher_pictures/no_image.jpg" width="100" alt="" />
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
