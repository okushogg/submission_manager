<?php
/* Smarty version 4.1.1, created on 2022-07-25 07:42:30
  from '/Applications/MAMP/htdocs/submissions_manager/templates/student/index_submission.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62de49662557e6_42314623',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd5c564c9f7a47f5a80d5cd388203e067aec53836' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/student/index_submission.tpl',
      1 => 1658731698,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62de49662557e6_42314623 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $_smarty_tpl->tpl_vars['all_subjects']->value[$_smarty_tpl->tpl_vars['subject_id']->value]['name'];?>
課題一覧</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1><?php echo $_smarty_tpl->tpl_vars['all_subjects']->value[$_smarty_tpl->tpl_vars['subject_id']->value]['name'];?>
課題一覧</h1>
    </div>
    <div id="content">
      <?php if ((isset($_SESSION['auth']['teacher_id']))) {?>
        <div style="text-align: right"><a href="../teacher/home.php">教員ホームへ</a></div>
      <?php }?>
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php?year=<?php echo $_smarty_tpl->tpl_vars['chosen_class']->value['year'];?>
">ホーム</a></div>
      <div style="text-align: left">
        <div style="margin: 10px">
          <p>クラス</p>
            <div style="display: flex">
              <?php echo $_smarty_tpl->tpl_vars['chosen_class']->value['year'];?>
年度<?php echo $_smarty_tpl->tpl_vars['chosen_class']->value['grade'];?>
年<?php echo $_smarty_tpl->tpl_vars['chosen_class']->value['class'];?>
組
            </div>
        </div>
        <img src="../student_pictures/<?php echo $_smarty_tpl->tpl_vars['student_pic_info']->value['path'];?>
" width="100" height="100" alt="" />
        <?php echo $_smarty_tpl->tpl_vars['chosen_class']->value['grade'];?>
 - <?php echo $_smarty_tpl->tpl_vars['chosen_class']->value['class'];?>
 No_<?php echo $_smarty_tpl->tpl_vars['chosen_class']->value['student_num'];?>

        <?php echo $_smarty_tpl->tpl_vars['student_info']->value['student_last_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['student_info']->value['student_first_name'];?>
さん
      </div>

      <!-- 課題一覧 -->
      <div>
        <?php if ($_smarty_tpl->tpl_vars['submission_info']->value) {?>
          <form action="" , method="post">
            <table class="" style="text-align: center">
              <tr>
                <!-- <th>h_id</th> -->
                <th>課題名</th>
                <th>提出期限</th>
                <th>受領日</th>
                <th>評価</th>
              </tr>
              <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['submission_info']->value, 'submission');
$_smarty_tpl->tpl_vars['submission']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['submission']->value) {
$_smarty_tpl->tpl_vars['submission']->do_else = false;
?>

                <!-- student_submissions_id -->
                <!-- <td>
                <?php echo $_smarty_tpl->tpl_vars['submission']->value['student_submissions_id'];?>

              </td> -->

                <!-- 課題名 -->
                <td>
                  <?php echo $_smarty_tpl->tpl_vars['submission']->value['submission_name'];?>

                </td>

                <!-- 提出期限 -->
                <td>
                  <?php echo $_smarty_tpl->tpl_vars['submission']->value['dead_line'];?>

                </td>

                <!-- 受領日 -->
                <td>
                  <?php echo $_smarty_tpl->tpl_vars['submission']->value['approved_date'];?>

                </td>

                <!-- スコア -->
                <td>
                  <?php echo $_smarty_tpl->tpl_vars['scoreList']->value[$_smarty_tpl->tpl_vars['submission']->value['score']];?>

                </td>
                </tr>
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </table>
          <?php } else { ?>
            <p>課題はありません</p>
          <?php }?>


</body>

</html><?php }
}
