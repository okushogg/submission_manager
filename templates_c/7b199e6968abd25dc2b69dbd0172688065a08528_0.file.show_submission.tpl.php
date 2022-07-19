<?php
/* Smarty version 4.1.1, created on 2022-07-19 14:29:16
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/show_submission.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62d65d4c5e2774_98544044',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '7b199e6968abd25dc2b69dbd0172688065a08528' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/show_submission.tpl',
      1 => 1658215753,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62d65d4c5e2774_98544044 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>課題入力ページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>評価入力ページ</h1>
    </div>


    <div id="content">
      <!-- ナビゲーション -->
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="edit_submission.php?submission_id=<?php echo $_smarty_tpl->tpl_vars['submission_id']->value;?>
">課題編集</a></div>
      <div style="text-align: right"><a href="delete_submission.php?submission_id=<?php echo $_smarty_tpl->tpl_vars['submission_id']->value;?>
">課題削除</a></div>
      <div style="text-align: right"><a href="index_submission.php?class_id=<?php echo $_smarty_tpl->tpl_vars['submission_info']->value['class_id'];?>
">課題一覧へ</a></div>

      <!-- ユーザー情報 -->
      <div style="text-align: left">
        <img src="../teacher_pictures/<?php echo $_smarty_tpl->tpl_vars['pic_info']->value['path'];?>
" width="100" height="100" alt="" />
        <?php echo $_smarty_tpl->tpl_vars['teacher_info']->value['last_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['teacher_info']->value['first_name'];?>
先生
      </div>

      <!-- 課題情報 -->
      <div>
        <h3><?php echo $_smarty_tpl->tpl_vars['submission_info']->value['grade'];?>
-<?php echo $_smarty_tpl->tpl_vars['submission_info']->value['class'];?>
</h3>
        <h3><?php echo $_smarty_tpl->tpl_vars['submission_info']->value['subject_name'];?>
</h3>
        <h1><?php echo $_smarty_tpl->tpl_vars['submission_info']->value['name'];?>
</h1>
      </div>

      <!-- 生徒一覧 -->
      <div>
        <form action="" , method="post">
          <table class="">
            <tr>
              <!-- <th>h_id</th> -->
              <th>No.</th>
              <th>生徒名</th>
              <th>提出期限</th>
              <th>受領日</th>
              <th>評価</th>
            </tr>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['students_who_have_submission']->value, 'student');
$_smarty_tpl->tpl_vars['student']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['student']->value) {
$_smarty_tpl->tpl_vars['student']->do_else = false;
?>
              <!-- 出席番号 -->
              <td>
                <?php ob_start();
echo $_smarty_tpl->tpl_vars['student']->value['student_id'];
$_prefixVariable1 = ob_get_clean();
echo $_smarty_tpl->tpl_vars['student_num_array']->value[$_prefixVariable1]['student_num'];?>

              </td>

              <!-- 生徒名 -->
              <td>
                <a href="../student/home.php?student_id=<?php echo $_smarty_tpl->tpl_vars['student']->value['student_id'];?>
">
                  <?php echo $_smarty_tpl->tpl_vars['student']->value['last_name'];
echo $_smarty_tpl->tpl_vars['student']->value['first_name'];?>

                </a>
              </td>

              <!-- 提出期限 -->
              <?php if ($_smarty_tpl->tpl_vars['student']->value['dead_line'] <= $_smarty_tpl->tpl_vars['today']->value && $_smarty_tpl->tpl_vars['student']->value['score'] == null || 0) {?>
                <td style="color: red;">
                  <?php echo $_smarty_tpl->tpl_vars['student']->value['dead_line'];?>

                </td>
              <?php } else { ?>
                <td>
                  <?php echo $_smarty_tpl->tpl_vars['student']->value['dead_line'];?>

                </td>
              <?php }?>

              <!-- 受領日 -->
              <td>
                <?php echo $_smarty_tpl->tpl_vars['student']->value['approved_date'];?>

              </td>

              <!-- スコア -->
              <td>
                <select size="1" name="score[<?php echo $_smarty_tpl->tpl_vars['student']->value['student_submissions_id'];?>
]">
                  <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['scoreList']->value, 'value', false, 'key');
$_smarty_tpl->tpl_vars['value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->do_else = false;
?>
                    <?php $_smarty_tpl->_assignInScope('student_score_int', intval($_smarty_tpl->tpl_vars['student']->value['score']));?>
                    <?php if ((isset($_smarty_tpl->tpl_vars['student']->value['score'])) && $_smarty_tpl->tpl_vars['value']->value === $_smarty_tpl->tpl_vars['student_score_int']->value) {?>
                      <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
" selected><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
 </option>
                    <?php } else { ?>
                      <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
</option>
                    <?php }?>
                  <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                  <?php echo '?>'; ?>

                </select>
              </td>
              </tr>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
          </table>
          <div><input type="submit" value="評価を更新" /></div>
        </form>
      </div>


</body>

</html><?php }
}
