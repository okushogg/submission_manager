<?php
/* Smarty version 4.1.1, created on 2022-07-26 03:57:20
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/create_submission.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62df6620465e65_92163401',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '92fe9ba113cff418f27aeb06c9225f9095516dde' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/create_submission.tpl',
      1 => 1658807833,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl' => 1,
  ),
),false)) {
function content_62df6620465e65_92163401 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>教員 課題作成ページ</title>
  <link rel="stylesheet" href="../style.css" />
  <?php echo '<script'; ?>
 type="text/javascript" src="../private/js/create_submission.js"><?php echo '</script'; ?>
>
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員 課題作成ページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>
            <?php $_smarty_tpl->_subTemplateRender("file:/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

      <div>
        <form action="" method="post" enctype="multipart/form-data">
          <dl>
            <dt>課題名<span class="required">（必須）</span></dt>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['submission_name'])) && $_smarty_tpl->tpl_vars['error']->value['submission_name'] === 'blank') {?>
              <p class="error">* 課題名を入力してください</p>
            <?php }?>
            <dd>
              <input type="text" name="submission_name" size="35" maxlength="255" value="<?php echo $_smarty_tpl->tpl_vars['form']->value['submission_name'];?>
" />
            </dd>

            <dt>クラス<span class="required">（必須）</span></dt>
            <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['class_id'])) && $_smarty_tpl->tpl_vars['error']->value['class_id'] === 'blank') {?>
              <p class="error">* クラスを入力してください</p>
            <?php }?>
            <dd>
              <div id="class_select">
                <select id="class_0" size="1" name="class_id">
                  <option value="0">-</option>
                  <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['classes_info']->value, 'class');
$_smarty_tpl->tpl_vars['class']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['class']->value) {
$_smarty_tpl->tpl_vars['class']->do_else = false;
?>
                    <?php if ($_smarty_tpl->tpl_vars['form']->value['class_id'] == $_smarty_tpl->tpl_vars['class']->value['id']) {?>
                      <option value=<?php echo $_smarty_tpl->tpl_vars['class']->value['id'];?>
 selected> <?php echo $_smarty_tpl->tpl_vars['class']->value['grade'];?>
 - <?php echo $_smarty_tpl->tpl_vars['class']->value['class'];?>
</option>
                    <?php } else { ?>
                      <option value=<?php echo $_smarty_tpl->tpl_vars['class']->value['id'];?>
> <?php echo $_smarty_tpl->tpl_vars['class']->value['grade'];?>
 - <?php echo $_smarty_tpl->tpl_vars['class']->value['class'];?>
</option>
                    <?php }?>
                  <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                </select>
              </div>
            </dd>

            <div style="margin-top: 10px; margin-bottom: 10px;">
              <input type="button" value="フォーム追加" onclick="addForm()">
            </div>

            <dt>教科<span class="required">（必須）</span></dt>
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
                  <?php if ($_smarty_tpl->tpl_vars['form']->value['subject_id'] == $_smarty_tpl->tpl_vars['subject']->value['id']) {?>
                    <option value="<?php echo $_smarty_tpl->tpl_vars['subject']->value['id'];?>
" selected> <?php echo $_smarty_tpl->tpl_vars['subject']->value['name'];?>
 </option>
                  <?php } else { ?>
                    echo "<option value="<?php echo $_smarty_tpl->tpl_vars['subject']->value['id'];?>
"> <?php echo $_smarty_tpl->tpl_vars['subject']->value['name'];?>
 </option>
                  <?php }?>
                <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
              </select>
            </dd>

            <dt>提出期限<span class="required">（必須）</span></dt>
             <?php if ((isset($_smarty_tpl->tpl_vars['error']->value['dead_line'])) && $_smarty_tpl->tpl_vars['error']->value['dead_line'] === 'blank') {?>
              <p class="error">* 提出期限を入力してください</p>
             <?php } elseif ((isset($_smarty_tpl->tpl_vars['error']->value['dead_line'])) && $_smarty_tpl->tpl_vars['error']->value['dead_line'] === 'not_future_date') {?>
              <p class="error">* 提出期限は本日以降の日付を入力してください</p>
             <?php }?>
            <dd>
              <input type="date" name="dead_line" value="<?php echo $_smarty_tpl->tpl_vars['form']->value['dead_line'];?>
" />
            </dd>

            <dd>
              <input type="hidden" name="teacher_id" value=<?php echo $_smarty_tpl->tpl_vars['teacher_info']->value['teacher_id'];?>
 />
            </dd>
          </dl>
          <div><input type="submit" value="課題を作成" /></div>
        </form>
      </div>

      <?php echo '<script'; ?>
 type="text/javascript" src="../submission.js"><?php echo '</script'; ?>
>
</body>

</html><?php }
}
