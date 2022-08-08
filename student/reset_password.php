<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
require_once('../model/students.php');

$smarty = new Smarty_submission_manager();
$student = new student();

// header tittle
$title = "生徒パスワードリセットページ";
$smarty->assign('title', $title);

// 現在のバンコクの時刻
$current_time = bkk_time();

$email = "";

// 送信をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $success_pw_reset = $student->set_password_reset_token($db, $email, $current_time);
  if ($success_pw_reset) {
    // DBへtokenが保存されたらメールを送信
    send_mail($account_holder['email'], $password_reset_token, "student");
  } else {
    // メールアドレスがなかった場合もログインページへ
    header('Location: log_in.php');
    exit();
  }
  // ログインページへ
  header('Location: log_in.php');
  exit();
}

$smarty->caching = 0;
$smarty->display('student/reset_password.tpl');
