<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
require_once('../model/teachers.php');

$smarty = new Smarty_submission_manager();
$teacher = new teacher();

// header tittle
$title = "教員 パスワードリセットページ";
$smarty->assign('title', $title);

// 現在のバンコクの時刻
$current_time = bkk_time();

$email = "";

is_teacher_login();

// 送信をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $teacher->set_password_reset_token($db, $email, $current_time);
  // メールアドレスがなかった場合もログインページへ
  header('Location: log_in.php');
  exit();
}
$smarty->caching = 0;
$smarty->display('teacher/password_reset.tpl');
