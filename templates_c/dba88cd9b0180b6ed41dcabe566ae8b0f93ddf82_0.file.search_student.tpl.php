<?php
/* Smarty version 4.1.1, created on 2022-07-19 10:08:21
  from '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/search_student.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.1.1',
  'unifunc' => 'content_62d68295294116_53902100',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dba88cd9b0180b6ed41dcabe566ae8b0f93ddf82' => 
    array (
      0 => '/Applications/MAMP/htdocs/submissions_manager/templates/teacher/search_student.tpl',
      1 => 1658225291,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_62d68295294116_53902100 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>生徒検索ページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒検索ページ</h1>
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

      <p>生徒検索</p>
      <form action="" method="post">
        <span>年度</span>
        <select size="1" name="year">
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['all_years']->value, 'y');
$_smarty_tpl->tpl_vars['y']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['y']->value) {
$_smarty_tpl->tpl_vars['y']->do_else = false;
?>
            <?php if ($_smarty_tpl->tpl_vars['y']->value['year'] == $_smarty_tpl->tpl_vars['form']->value['year']) {?>
              <option value="<?php echo $_smarty_tpl->tpl_vars['y']->value['year'];?>
" selected> <?php echo $_smarty_tpl->tpl_vars['y']->value['year'];?>
 </option>
            <?php } else { ?>
              <option value="<?php echo $_smarty_tpl->tpl_vars['y']->value['year'];?>
"> <?php echo $_smarty_tpl->tpl_vars['y']->value['year'];?>
 </option>
            <?php }?>
          <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </select>

        <span>学年</span>
        <select size="1" name="grade">
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['grades']->value, 'value', false, 'key');
$_smarty_tpl->tpl_vars['value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->do_else = false;
?>
            <?php if ($_smarty_tpl->tpl_vars['value']->value == $_smarty_tpl->tpl_vars['form']->value['grade']) {?>
              <option value=<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
 selected> <?php echo $_smarty_tpl->tpl_vars['key']->value;?>
 </option>
            <?php } else { ?>
              <option value=<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
> <?php echo $_smarty_tpl->tpl_vars['key']->value;?>
 </option>
            <?php }?>
          <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </select>

        <span>クラス</span>
        <select size="1" name="class">
          <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['classes']->value, 'value', false, 'key');
$_smarty_tpl->tpl_vars['value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->do_else = false;
?>
            <?php if ($_smarty_tpl->tpl_vars['value']->value == $_smarty_tpl->tpl_vars['form']->value['class']) {?>
              <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
" selected> <?php echo $_smarty_tpl->tpl_vars['key']->value;?>
 </option>
            <?php } else { ?>
              <option value="<?php echo $_smarty_tpl->tpl_vars['value']->value;?>
"> <?php echo $_smarty_tpl->tpl_vars['key']->value;?>
 </option>
            <?php }?>
          <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
        </select>
        <input type="radio" name="is_active" value=0 <?php if ($_smarty_tpl->tpl_vars['form']->value['is_active'] === "0") {?>'checked'<?php }?>>除籍
        <input type="radio" name="is_active" value=1 <?php if ($_smarty_tpl->tpl_vars['form']->value['is_active'] === "1") {?>'checked'<?php }?>>在籍
        <br>
        <span>氏</span>
        <input type="text" name="last_name" size="20" maxlength="20" value="<?php echo $_smarty_tpl->tpl_vars['form']->value['last_name'];?>
" />
        <span>名</span>
        <input type="text" name="first_name" size="20" maxlength="20" value="<?php echo $_smarty_tpl->tpl_vars['form']->value['first_name'];?>
" />
        <input type="submit" value="検索" />
      </form>

      <?php if ((isset($_smarty_tpl->tpl_vars['student_search_result']->value))) {?>

        <!-- 生徒検索結果一覧 -->
        <div style="margin: 15px;">
          <table class="" style="text-align: center;">
            <tr>
              <th>学年</th>
              <th>クラス</th>
              <th>出席番号</th>
              <th>氏名</th>
              <th>在籍状況</th>
            </tr>

            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['student_search_result']->value, 'student');
$_smarty_tpl->tpl_vars['student']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['student']->value) {
$_smarty_tpl->tpl_vars['student']->do_else = false;
?>

              <!-- 学年 -->
              <td>
                <?php echo $_smarty_tpl->tpl_vars['student']->value['grade'];?>

              </td>

              <!-- クラス -->
              <td>
                <?php echo $_smarty_tpl->tpl_vars['student']->value['class'];?>

              </td>

              <!-- 出席番号 -->
              <td>
                <?php echo $_smarty_tpl->tpl_vars['student']->value['student_num'];?>

              </td>

              <!-- 生徒氏名 -->
              <td>
                <a href="../student/home.php?student_id=<?php echo $_smarty_tpl->tpl_vars['student']->value['student_id'];?>
">
                  <?php echo $_smarty_tpl->tpl_vars['student']->value['last_name'];?>
 <?php echo $_smarty_tpl->tpl_vars['student']->value['first_name'];?>

                </a>
              </td>

              <!-- 在籍状況 -->
              <?php if ($_smarty_tpl->tpl_vars['student']->value['is_active'] == 0) {?>
                <td style="color: red;">
                  除籍済
                </td>
              <?php } else { ?>
                <td>
                  在籍
                </td>
              <?php }?>
              </tr>
            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
          </table>
        </div>

      <?php }?>


</body>

</html><?php }
}
