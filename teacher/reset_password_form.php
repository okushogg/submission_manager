<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');
require('../private/error_check.php');

require_once('../private/set_up.php');
require_once('../model/teachers.php');

$smarty = new Smarty_submission_manager();
$teacher = new teacher();


// header tittle
$title = "教員 パスワードリセットページ";
$smarty->assign('title', $title);


// 現在のバンコクの時刻
$current_time = bkk_time();

// urlからpassword_reset_tokenを取得
$password_reset_token = filter_input(INPUT_GET, 'password_reset_token');

// フォームの初期化
$form = [
  "password" => ''
];

// エラーの初期化
$error =[];

$smarty->assign('form', $form);
$smarty->assign('error', $error);

// パスワード再設定をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // エラーチェック
  list($error, $form) = error_check($this_year, $today, $form, "teachers");

  if (empty($error)) {
    // フォームに入力されたパスワードをハッシュ化
    $password = password_hash($form['password'], PASSWORD_DEFAULT);
    $teacher->reset_password($current_time, $password, $password_reset_token);
    header("Location: log_in.php");
    exit();
  }
  $smarty->assign('form', $form);
  $smarty->assign('error', $error);
}
$smarty->caching = 0;
$smarty->display('teacher/password_reset_form.tpl');
?>