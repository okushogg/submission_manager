<?php
/* Smarty version 4.1.1, created on 2022-07-22 04:19:26
  from '/Applications/MAMP/htdocs/submissions_manager/templates/student/sign_up.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62da254eba0611_18501910',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '476544783135c37cf279de881208f432b46952a0' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/student/sign_up.tpl',
      1 => 1658463362,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62da254eba0611_18501910 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>生徒登録</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>

  <body>
    <div id="wrap">
      <div id="head">
        <h1>生徒登録</h1>
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

            <dt>性別<span class="required">（必須）</span></dt>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['sex'])) && $_smarty_tpl->tpl_vars['error']->value['sex'] === 'blank') {?>
              <p class="error">* 性別を入力してください</p>
            <?php }?>
            <dd>
              <input type="radio" name="sex" value=0 <?php if ($_smarty_tpl->tpl_vars['form']->value['sex'] === "0") {?> checked <?php }?>>男
              <input type="radio" name="sex" value=1 <?php if ($_smarty_tpl->tpl_vars['form']->value['sex'] === "1") {?> checked <?php }?>>女
            </dd>

            <dt>クラス<span class="required">（必須）</span></dt>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['class_id'])) && $_smarty_tpl->tpl_vars['error']->value['class_id'] === 'blank') {?>
              <p class="error">* クラスを入力してください</p>
            <?php }?>
            <dd>
              <select size="1" name="class_id">
                <option value="0">-</option>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['this_year_classes']->value, 'class');
$_smarty_tpl->tpl_vars['class']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['class']->value) {
$_smarty_tpl->tpl_vars['class']->do_else = false;
?>
                  <?php if ($_smarty_tpl->tpl_vars['form']->value['class_id'] == $_smarty_tpl->tpl_vars['class']->value['id']) {?>
                   <option value="<?php echo $_smarty_tpl->tpl_vars['class']->value['id'];?>
" selected> <?php echo $_smarty_tpl->tpl_vars['class']->value['grade'];?>
-<?php echo $_smarty_tpl->tpl_vars['class']->value['class'];?>
</option>
                  <?php } else { ?>
                   <option value="<?php echo $_smarty_tpl->tpl_vars['class']->value['id'];?>
"> <?php echo $_smarty_tpl->tpl_vars['class']->value['grade'];?>
-<?php echo $_smarty_tpl->tpl_vars['class']->value['class'];?>
</option>
                  <?php }?>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
              </select>
            </dd>

            <dt>出席番号<span class="required">（必須）</span></dt>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['student_num'])) && $_smarty_tpl->tpl_vars['error']->value['student_num'] === 'blank') {?>
              <p class="error">* 出席番号を入力してください</p>
            <?php }?>
            <dd>
              <input type="number" min="1" max="40" name="student_num" value="<?php echo $_smarty_tpl->tpl_vars['form']->value['student_num'];?>
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
            </dd>

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
            <dd>
              <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['image'])) && $_smarty_tpl->tpl_vars['error']->value['image'] === 'type') {?>
                <p class="error">* 写真などは「.png」または「.jpg」の画像を指定してください</p>
                <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
              <?php }?>
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
