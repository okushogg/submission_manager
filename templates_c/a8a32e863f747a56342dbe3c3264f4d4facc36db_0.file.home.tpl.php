<?php
/* Smarty version 4.1.1, created on 2022-07-25 06:04:28
  from '/Applications/MAMP/htdocs/submissions_manager/templates/student/home.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62de326c106f40_03304306',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a8a32e863f747a56342dbe3c3264f4d4facc36db' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/student/home.tpl',
      1 => 1658729061,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62de326c106f40_03304306 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>生徒トップページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒トップページ</h1>
    </div>
    <div id="content">
     <?php if ((isset($_smarty_tpl->tpl_vars['teacher_id']->value))) {?>
        <div style="text-align: right"><a href="../teacher/home.php">教員ホームへ</a></div>
     <?php }?>

      <div style="text-align: right">
       <?php if ((!$_smarty_tpl->tpl_vars['this_year_class']->value)) {?>
          <span class="required">要新規登録</span>
       <?php }?>
        <a href="edit_student.php">生徒情報編集ページ</a>
      </div>

      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: left">

        <!-- 所属クラス -->
        <div style="margin-top: 10px; margin-bottom: 10px;">
          <p>所属クラス</p>
          <form action="" method="post">
            <select size="1" name="year">
             <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['belonged_classes']->value, 'belonged_class');
$_smarty_tpl->tpl_vars['belonged_class']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['belonged_class']->value) {
$_smarty_tpl->tpl_vars['belonged_class']->do_else = false;
?>
                <?php if ($_smarty_tpl->tpl_vars['belonged_class']->value['year'] == $_smarty_tpl->tpl_vars['form']->value['year']) {?>
                  <option value="<?php echo $_smarty_tpl->tpl_vars['belonged_class']->value['year'];?>
" selected>
                    <?php echo $_smarty_tpl->tpl_vars['belonged_class']->value['year'];?>
年度<?php echo $_smarty_tpl->tpl_vars['belonged_class']->value['grade'];?>
年<?php echo $_smarty_tpl->tpl_vars['belonged_class']->value['class'];?>
組
                  </option>
                <?php } else { ?>
                  <option value="<?php echo $_smarty_tpl->tpl_vars['belonged_class']->value['year'];?>
">
                    <?php echo $_smarty_tpl->tpl_vars['belonged_class']->value['year'];?>
年度<?php echo $_smarty_tpl->tpl_vars['belonged_class']->value['grade'];?>
年<?php echo $_smarty_tpl->tpl_vars['belonged_class']->value['class'];?>
組
                  </option>
                <?php }?>
              <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
            </select>
            <input type="submit" value="変更" />
          </form>
        </div>
        <!-- 生徒情報 -->
        <div>
          <img src="../student_pictures/<?php echo $_smarty_tpl->tpl_vars['student_pic_info']->value['path'];?>
" width="100" height="100" alt="" />
         <?php if ($_smarty_tpl->tpl_vars['this_year_class']->value) {?>
           <?php echo $_smarty_tpl->tpl_vars['this_year_class']->value['grade'];?>
-<?php echo $_smarty_tpl->tpl_vars['this_year_class']->value['class'];?>
 No_<?php echo $_smarty_tpl->tpl_vars['this_year_class']->value['student_num'];?>

         <?php }?>
           <?php echo $_smarty_tpl->tpl_vars['student_info']->value['student_last_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['student_info']->value['student_first_name'];?>
さん
         <?php if ($_smarty_tpl->tpl_vars['student_info']->value['is_active'] == 0) {?>
            <p style="color: red;">除籍済</p>
         <?php }?>
        </div>

       <?php if ($_smarty_tpl->tpl_vars['this_year_class']->value) {?>
          <div>
            <p> 各教科 課題一覧</p>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['all_subjects']->value, 'subject');
$_smarty_tpl->tpl_vars['subject']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['subject']->value) {
$_smarty_tpl->tpl_vars['subject']->do_else = false;
?>
              <span style="margin: 15px;">
                <a href="index_submission.php?subject_id=<?php echo $_smarty_tpl->tpl_vars['subject']->value['subject_id'];?>
&class_id=<?php echo $_smarty_tpl->tpl_vars['class_id']->value;?>
">
                 <?php echo $_smarty_tpl->tpl_vars['subject']->value['name'];?>

                </a>
              </span>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
          </div>

         <?php if ($_smarty_tpl->tpl_vars['submission_info']->value) {?>
            <!-- 課題一覧 -->
            <div style="margin: 15px;">
              <table class="" style="text-align: center;">
                <tr>
                  <!-- <th>h_id</th> -->
                  <th>教科</th>
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
                  <!-- 教科 -->
                  <td>
                   <?php echo $_smarty_tpl->tpl_vars['all_subjects']->value[$_smarty_tpl->tpl_vars['submission']->value['subject_id']]['name'];?>

                  </td>

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
                 <?php if ($_smarty_tpl->tpl_vars['submission']->value['score'] === "0") {?>
                    <td style="color: red;">
                     <?php echo $_smarty_tpl->tpl_vars['scoreList']->value[$_smarty_tpl->tpl_vars['submission']->value['score']];?>

                    </td>
                 <?php } else { ?>
                    <td>
                     <?php echo $_smarty_tpl->tpl_vars['scoreList']->value[$_smarty_tpl->tpl_vars['submission']->value['score']];?>

                    </td>
                 <?php }?>
                  </tr>
               <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
              </table>
           <?php } else { ?>
              <p>期限の近い課題はありません</p>
           <?php }?>
        <?php }?>
</body>

</html><?php }
}
