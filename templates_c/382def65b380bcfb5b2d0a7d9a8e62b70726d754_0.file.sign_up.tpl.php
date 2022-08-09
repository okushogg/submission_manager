<?php
/* Smarty version 4.1.1, created on 2022-08-08 12:12:52
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/sign_up.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62f0fdc4787358_45727348',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '382def65b380bcfb5b2d0a7d9a8e62b70726d754' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/sign_up.tpl',
      1 => 1659670486,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/header.tpl' => 1,
  ),
),false)) {
function content_62f0fdc4787358_45727348 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="en">

<?php $_smarty_tpl->_subTemplateRender("file:../common/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<body>

  <body>
    <div id="wrap">
      <div id="head">
        <h1>教員登録</h1>
      </div>

      <div id="content">
        <p>&raquo;<a href="log_in.php">ログインページ</a></p>
        <p>次のフォームに必要事項をご記入ください。</p>
        <form action="" method="post" enctype="multipart/form-data">
          <dl>
            <dt>氏<span class="required">（必須）</span></dt>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['last_name'])) && $_smarty_tpl->tpl_vars['error']->value['last_name'] === 'blank') {?>
              <p class="error">* 苗字を入力してください</p>
            <?php } elseif ((isset($_smarty_tpl->tpl_vars['error']->value['last_name'])) && $_smarty_tpl->tpl_vars['error']->value['last_name'] === 'invalid_letter') {?>
              <p class="error">* 全角ひらがな、カタカナ、漢字で入力してください</p>
            <?php }?>
            <dd>
              <input type="text" name="last_name" size="35" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['form']->value['last_name'];?>
" />
            </dd>

            <dt>名<span class="required">（必須）</span></dt>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['first_name'])) && $_smarty_tpl->tpl_vars['error']->value['first_name'] === 'blank') {?>
              <p class="error">* 名前を入力してください</p>
            <?php } elseif ((isset($_smarty_tpl->tpl_vars['error']->value['first_name'])) && $_smarty_tpl->tpl_vars['error']->value['first_name'] === 'invalid_letter') {?>
              <p class="error">* 全角ひらがな、カタカナ、漢字で入力してください</p>
            <?php }?>
            <dd>
              <input type="text" name="first_name" size="35" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['form']->value['first_name'];?>
" />
            </dd>

            <dt>メールアドレス<span class="required">（必須）</span></dt>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['email'])) && $_smarty_tpl->tpl_vars['error']->value['email'] === 'blank') {?>
              <p class="error">* メールアドレスを入力してください</p>
            <?php } elseif ((isset($_smarty_tpl->tpl_vars['error']->value['email'])) && $_smarty_tpl->tpl_vars['error']->value['email'] === 'not_like_email') {?>
              <p class="error">* メールアドレスの形式ではないようです</p>
            <?php } elseif ((isset($_smarty_tpl->tpl_vars['error']->value['email'])) && $_smarty_tpl->tpl_vars['error']->value['email'] === 'duplicate') {?>
              <p class="error">* 登録済のメールアドレスです</p>
            <?php }?>
            <dd>
              <input type="text" name="email" size="35" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['form']->value['email'];?>
" />

            <dt>パスワード<span class="required">（必須）</span></dt>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['password'])) && $_smarty_tpl->tpl_vars['error']->value['password'] === 'blank') {?>
              <p class="error">* パスワードを入力してください</p>
            <?php } elseif ((isset($_smarty_tpl->tpl_vars['error']->value['password'])) && $_smarty_tpl->tpl_vars['error']->value['password'] === 'length') {?>
              <p class="error">* パスワードは4文字以上で入力してください</p>
            <?php } elseif ((isset($_smarty_tpl->tpl_vars['error']->value['password'])) && $_smarty_tpl->tpl_vars['error']->value['password'] === 'invalid_letter') {?>
              <p class="error">* パスワードは半角英数字で入力してください</p>
            <?php }?>
            <dd>
              <input type="password" name="password" size="10" maxlength="20" value="<?php echo $_smarty_tpl->tpl_vars['form']->value['password'];?>
" />
            </dd>

            <dt>写真など</dt>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['image'])) && $_smarty_tpl->tpl_vars['error']->value['image'] === 'type') {?>
                <p class="error">* 写真などは「.png」または「.jpg」の画像を指定してください</p>
                <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
            <?php } elseif ((isset($_smarty_tpl->tpl_vars['error']->value['image'])) && $_smarty_tpl->tpl_vars['error']->value['image'] === 'size') {?>
                <p class="error">* 500KB以下の画像を指定してください </p>
            <?php }?>
            <dd>
              <input type="file" name="image" size="35" value="" />
            </dd>

            <dd>
              <input type="hidden" name="is_active" value=true />
            </dd>
          </dl>
          <div><input type="submit" value="入力内容を確認する" /></div>
        </form>
      </div>
  </body>

</html><?php }
}
