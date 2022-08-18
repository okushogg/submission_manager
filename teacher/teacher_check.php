<?php
session_start();
require('../private/libs.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

// header tittle
$title = "教員 確認ページ";
$smarty->assign('title', $title);

$error = [];
$password = '';
$teacher_check_password = "password";
$smarty->assign('error', $error);
$smarty->assign('password', $password);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $password = filter_input(INPUT_POST, 'password');
  if ($password === '') {
    $error['teacher_check'] = 'blank';
  } else {
    // teacherページへ遷移するためのパスワードチェック
    if($password === $teacher_check_password){
      // 成功時はloginページへ
      header('Location: log_in.php');
      $_SESSION['auth']['pass_teacher_check'] = true;
      exit();
    } else {
      // 失敗時はエラー表示
      $error['teacher_check'] = 'auth_fail';
    }
  }
  $smarty->assign('password', $password);
  $smarty->assign('error', $error);
}

$smarty->caching = 0;
$smarty->display('teacher/teacher_check.tpl');
?>