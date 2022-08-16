<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');
require('../private/error_check.php');

require_once('../private/set_up.php');
require_once('../model/students.php');

$smarty = new Smarty_submission_manager();
$student = new student();

// header tittle
$title = "生徒パスワードリセットページ";
$smarty->assign('title', $title);

// 現在のバンコクの時刻
$current_time = bkk_time();

// urlからpassword_reset_tokenを取得
$password_reset_token = filter_input(INPUT_GET, 'password_reset_token', FILTER_SANITIZE_SPECIAL_CHARS);

// フォームの初期化
$form = [
  "password" => ''
];
$smarty->assign('form', $form);

// エラーの初期化
$error = [];

// パスワード再設定をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // エラーチェック
  list($error, $form) = error_check($this_year, $today, $form, "students");

  if (empty($error)) {
    // フォームに入力されたパスワードをハッシュ化
    $password = password_hash($form['password'], PASSWORD_DEFAULT);
    $student->reset_password($current_time, $password, $password_reset_token);
  }
  $smarty->assign('form', $form);
  $smarty->assign('error', $error);
}
$smarty->caching = 0;
$smarty->display('student/reset_password_form.tpl');
?>