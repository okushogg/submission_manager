<?php
/* Smarty version 4.1.1, created on 2022-07-25 06:38:39
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/index_submission.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62de3a6f132750_61287852',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5d9120d8e59eead6fd08f6817c468bf4eb18a511' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/index_submission.tpl',
      1 => 1658475115,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl' => 1,
  ),
),false)) {
function content_62de3a6f132750_61287852 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $_smarty_tpl->tpl_vars['class_info']->value['grade'];?>
 - <?php echo $_smarty_tpl->tpl_vars['class_info']->value['class'];?>
 課題一覧ページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1><?php echo $_smarty_tpl->tpl_vars['class_info']->value['grade'];?>
 - <?php echo $_smarty_tpl->tpl_vars['class_info']->value['class'];?>
 課題一覧ページ</h1>
    </div>
    <div id="content">
      <!-- ナビゲーション -->
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="create_submission.php">課題作成</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>

      <!-- ユーザー情報 -->
            <?php $_smarty_tpl->_subTemplateRender("file:/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

      <!-- 課題一覧 -->
      <div>
      <?php if ($_smarty_tpl->tpl_vars['submission_info']->value) {?>
        <table class="">
          <tr>
            <th>課題名</th>
            <th>教科名</th>
            <th>提出期限</th>
          </tr>
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['submission_info']->value, 'submission');
$_smarty_tpl->tpl_vars['submission']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['submission']->value) {
$_smarty_tpl->tpl_vars['submission']->do_else = false;
?>
            <tr>
              <td>
                <a href="show_submission.php?submission_id=<?php echo $_smarty_tpl->tpl_vars['submission']->value['id'];?>
">
                  <?php echo $_smarty_tpl->tpl_vars['submission']->value['submission_name'];?>

                </a>
              </td>
              <td><?php echo $_smarty_tpl->tpl_vars['submission']->value['subject_name'];?>
</td>
              <td><?php echo $_smarty_tpl->tpl_vars['submission']->value['dead_line'];?>
</td>
            </tr>
          <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </table>
        <?php } else { ?>
            <p>課題はありません</p>
        <?php }?>
      </div>


</body>

</html><?php }
}
