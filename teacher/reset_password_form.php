<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

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

  // パスワードのチェック
  $form['password'] = filter_input(INPUT_POST, 'password');
  if ($form['password'] === '') {
    $error['password'] = 'blank';
  } elseif (strlen($form['password']) < 4) {
    $error['password'] = 'length';
  }

  if (empty($error)) {
    // フォームに入力されたパスワードをハッシュ化
    $password = password_hash($form['password'], PASSWORD_DEFAULT);

    // password_reset_tokenが一致しているteacherのpasswordを変更
    $stmt = $db->prepare("UPDATE teachers
                           SET password = :password,
                               updated_at = :updated_at,
                               password_reset_token = null
                         WHERE password_reset_token = :password_reset_token");
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);
    $stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
    $stmt->bindValue(':password_reset_token', $password_reset_token, PDO::PARAM_STR);

    $success = $stmt->execute();
    if (!$success) {
      $db->error;
    }
    header("Location: log_in.php");
    exit();
  }
  $smarty->assign('form', $form);
  $smarty->assign('error', $error);
}
$smarty->caching = 0;
$smarty->display('teacher/password_reset_form.tpl');
?>