<?php
/* Smarty version 4.1.1, created on 2022-07-19 14:54:22
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/edit_submission.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62d6632ee74902_01080832',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e4f251d12003bfc53393569542d1feab6293ca81' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/edit_submission.tpl',
      1 => 1658217259,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62d6632ee74902_01080832 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>教員 課題編集ページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員 課題編集ページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>
      <div style="text-align: left">
        <img src="../teacher_pictures/<?php echo $_smarty_tpl->tpl_vars['pic_info']->value['path'];?>
" width="100" height="100" alt="" />
        <?php echo $_smarty_tpl->tpl_vars['teacher_info']->value['last_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['teacher_info']->value['first_name'];?>
先生
      </div>

      <div>
        <form action="" method="post" enctype="multipart/form-data">
          <dl>
            <dt>課題名</dt>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['submission_name'])) && $_smarty_tpl->tpl_vars['error']->value['submission_name'] === 'blank') {?>
              <p class="error">* 課題名を入力してください</p>
            <?php }?>
            <dd>
              <input type="text" name="submission_name" size="35" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['submission_info']->value['name'];?>
" />
            </dd>

            <dt>クラス</dt>
            <dd>
              <?php echo $_smarty_tpl->tpl_vars['submission_info']->value['grade'];?>
 - <?php echo $_smarty_tpl->tpl_vars['submission_info']->value['class'];?>

            </dd>

            <dt>教科</dt>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['subject_id'])) && $_smarty_tpl->tpl_vars['error']->value['subject_id'] === 'blank') {?>
              <p class="error">* 教科を入力してください</p>
            <?php }?>
            <dd>
              <select size="1" name="subject_id">
                <option value="0">-</option>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['all_subjects']->value, 'subject');
$_smarty_tpl->tpl_vars['subject']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['subject']->value) {
$_smarty_tpl->tpl_vars['subject']->do_else = false;
?>
                  <?php if ($_smarty_tpl->tpl_vars['submission_info']->value['subject_id'] == $_smarty_tpl->tpl_vars['subject']->value['id']) {?>
                    <option value=<?php echo $_smarty_tpl->tpl_vars['subject']->value['id'];?>
 selected> <?php echo $_smarty_tpl->tpl_vars['subject']->value['name'];?>
 </option>
                <?php } else { ?>
                    <option value=<?php echo $_smarty_tpl->tpl_vars['subject']->value['id'];?>
> <?php echo $_smarty_tpl->tpl_vars['subject']->value['name'];?>
 </option>
                <?php }?>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
              </select>
            </dd>

            <dt>提出期限</dt>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['dead_line'])) && $_smarty_tpl->tpl_vars['error']->value['dead_line'] === 'blank') {?>
              <p class="error">* 提出期限を入力してください</p>
            <?php } elseif ((isset($_smarty_tpl->tpl_vars['error']->value['dead_line'])) && $_smarty_tpl->tpl_vars['error']->value['dead_line'] === 'not_future_date') {?>
              <p class="error">* 提出期限は本日以降の日付を入力してください</p>
            <?php }?>
            <dd>
              <input type="date" name="dead_line" value="<?php echo $_smarty_tpl->tpl_vars['submission_info']->value['dead_line'];?>
" />
            </dd>

            <dd>
              <input type="hidden" name="teacher_id" value=$id />
            </dd>
          </dl>
          <div><input type="submit" value="課題を編集" /></div>
        </form>

      </div>


</body>

</html><?php }
}
