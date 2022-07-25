<?php
/* Smarty version 4.1.1, created on 2022-07-25 13:47:36
  from '/Applications/MAMP/htdocs/submissions_manager/templates/student/edit_student.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62de3c88d48187_23453410',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'aca280802ac0812a5aec023c911e567a30735b8e' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/student/edit_student.tpl',
      1 => 1658731654,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62de3c88d48187_23453410 (Smarty_Internal_Template $_smarty_tpl) {
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
      <?php if ((isset($_SESSION['auth']['teacher_id']))) {?>
        <div style="text-align: right"><a href="../teacher/home.php">教員ホームへ</a></div>
      <?php }?>
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>
      <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
      <form action="" method="post" enctype="multipart/form-data">
        <dl>
          <dt>氏</dt>
          <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['last_name'])) && $_smarty_tpl->tpl_vars['error']->value['first_name'] === 'blank') {?>
            <p class="error">* 苗字を入力してください</p>
          <?php }?>
          <dd>
          <input type="text" name="last_name" size="35" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['student_info']->value['last_name'];?>
" />
          </dd>

          <dt>名</dt>
          <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['first_name'])) && $_smarty_tpl->tpl_vars['error']->value['first_name'] === 'blank') {?>
            <p class="error">* 名前を入力してください</p>
          <?php }?>
          <dd>
            <input type="text" name="first_name" size="35" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['student_info']->value['first_name'];?>
" />
          </dd>
          <dt>性別</dt>
          <?php if ((isset($_SESSION['auth']['teacher_id']))) {?>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['sex'])) && $_smarty_tpl->tpl_vars['error']->value['sex'] === null) {?>
              <p class="error">* 性別を入力してください</p>
            <?php }?>
            <dd>
              <input type="radio" name="sex" value=0 <?php if ($_smarty_tpl->tpl_vars['student_info']->value['sex'] === "0") {?> checked<?php }?>>男
              <input type="radio" name="sex" value=1 <?php if ($_smarty_tpl->tpl_vars['student_info']->value['sex'] === "1") {?> checked<?php }?>>女
            </dd>
          <?php } else { ?>
            <dd>
              <input type="hidden" name="sex" value="<?php echo $_smarty_tpl->tpl_vars['student_info']->value['sex'];?>
"/>
              <?php echo display_sex($_smarty_tpl->tpl_vars['student_info']->value['sex']);?>

            </dd>
          <?php }?>

          <dt>クラス</dt>
          <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['class_id'])) && $_smarty_tpl->tpl_vars['error']->value['class_id'] === 'blank') {?>
            <p class="error">* クラスを入力してください</p>
          <?php }?>
          <dd>
            <select size="1" name="class_id">
              <option value="0">-</option>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['selectable_classes']->value, 'class');
$_smarty_tpl->tpl_vars['class']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['class']->value) {
$_smarty_tpl->tpl_vars['class']->do_else = false;
?>
                <?php if ($_smarty_tpl->tpl_vars['class']->value['id'] == $_smarty_tpl->tpl_vars['this_year_class']->value['class_id']) {?>
                  <option value="<?php echo $_smarty_tpl->tpl_vars['class']->value['id'];?>
" selected> <?php echo $_smarty_tpl->tpl_vars['class']->value['grade'];?>
 - <?php echo $_smarty_tpl->tpl_vars['class']->value['class'];?>
</option>
                <?php } else { ?>
                  <option value="<?php echo $_smarty_tpl->tpl_vars['class']->value['id'];?>
"> <?php echo $_smarty_tpl->tpl_vars['class']->value['grade'];?>
 - <?php echo $_smarty_tpl->tpl_vars['class']->value['class'];?>
</option>
                <?php }?>
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </select>
            <?php if ($_smarty_tpl->tpl_vars['this_year']->value > $_smarty_tpl->tpl_vars['this_year_class']->value['year']) {?>
              <span class="required">要新規登録</span>
            <?php }?>
          </dd>

          <dt>出席番号</dt>
          <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['student_num'])) && $_smarty_tpl->tpl_vars['error']->value['student_num'] === 'blank') {?>
            <p class="error">* 出席番号を入力してください</p>
          <?php }?>
          <dd>
            <?php if ($_smarty_tpl->tpl_vars['this_year']->value > $_smarty_tpl->tpl_vars['this_year_class']->value['year']) {?>
              <input type="number" min="1" max="40" name="student_num" /><span class="required">要新規登録</span>
            <?php } else { ?>
              <input type="number" min="1" max="40" name="student_num" value="<?php echo $_smarty_tpl->tpl_vars['this_year_class']->value['student_num'];?>
" />
            <?php }?>
          </dd>

          <dt>メールアドレス</dt>
          <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['email'])) && $_smarty_tpl->tpl_vars['error']->value['email'] === 'blank') {?>
            <p class="error">* メールアドレスを入力してください</p>
          <?php }?>
          <dd>
            <input type="text" name="email" size="35" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['student_info']->value['email'];?>
");
          </dd>
          <dt>パスワード</dt>
          <dd>
            <p>パスワードの変更は<a href="reset_password.php">こちら</a>から。</p>
          </dd>

          <?php if ((isset($_SESSION['auth']['teacher_id'])) || $_smarty_tpl->tpl_vars['this_year']->value > $_smarty_tpl->tpl_vars['this_year_class']->value['year']) {?>
            <dt>写真など</dt>
            <dd>
              <input type="file" name="image" size="35" value="" />
              <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['image'])) && $_smarty_tpl->tpl_vars['error']->value['image'] === 'type') {?>
                <p class="error">* 写真などは「.png」または「.jpg」の画像を指定してください</p>
                <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
              <?php }?>
            </dd>
          <?php }?>



          <?php if ((isset($_SESSION['auth']['teacher_id']))) {?>
            <dt>在籍情報</dt>
            <dd>
              <input type="radio" name="is_active" value=0 <?php if ($_smarty_tpl->tpl_vars['student_info']->value['is_active'] == 0) {?> checked <?php }?>> 除籍
              <input type="radio" name="is_active" value=1 <?php if ($_smarty_tpl->tpl_vars['student_info']->value['is_active'] == 1) {?> checked <?php }?>> 在籍
            </dd>
          <?php } else { ?>
            <input type="hidden" name="is_active" value="<?php echo $_smarty_tpl->tpl_vars['student_info']->value['is_active'];?>
" />
          <?php }?>
        </dl>
        <div><input type="submit" value="生徒情報を更新" /></div>
    </div>

  </div>
</body>

</html><?php }
}
