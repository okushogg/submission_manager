<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

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

  // メールアドレスがteachersテーブルにあるか確認
  $stmt = $db->prepare("SELECT id as teacher_id, email
                      FROM teachers
                      WHERE email = :email
                      AND is_active = 1");
  $stmt->bindValue(':email', $email, PDO::PARAM_STR);
  $success = $stmt->execute();
  if (!$success) {
    $db->error;
  }
  $account_holder = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($account_holder) {
    // password reset token生成
    $password_reset_token = bin2hex(random_bytes(18));
    // メールを送信
    $mail_sent_success = send_mail($account_holder['email'], $password_reset_token, "teacher");
    if ($mail_sent_success) {
      // メールが送信されたらpassword_reset_tokenをteachersテーブルへ保存
      $pw_reset_stmt = $db->prepare("UPDATE teachers
                                     SET password_reset_token = :password_reset_token,
                                         updated_at = :updated_at
                                   WHERE id = :teacher_id ");
      $pw_reset_stmt->bindValue(':password_reset_token', $password_reset_token, PDO::PARAM_STR);
      $pw_reset_stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
      $pw_reset_stmt->bindValue(':teacher_id', $account_holder['teacher_id'], PDO::PARAM_INT);
      $success_pw_reset = $pw_reset_stmt->execute();
      if (!$success_pw_reset) {
        $db->error;
      }
      // ログインページへ
      header('Location: log_in.php');
      exit();
    }
  } else {
    // ログインページへ
    header('Location: log_in.php');
    exit();
  };
}
$smarty->caching = 0;
$smarty->display('teacher/password_reset.tpl');
?>