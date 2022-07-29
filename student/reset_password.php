<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

// header tittle
$title = "生徒パスワードリセットページ";
$smarty->assign('title', $title);

// 現在のバンコクの時刻
$current_time = bkk_time();

$email = "";



// 送信をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

  // メールアドレスがstudentsテーブルにあるか確認
  $stmt = $db->prepare("SELECT id as student_id, email
                      FROM students
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
    $mail_sent_success = send_mail($account_holder['email'], $password_reset_token, "student");
    if ($mail_sent_success) {
      // メールが送信されたらpassword_reset_tokenをstudentsテーブルへ保存
      $pw_reset_stmt = $db->prepare("UPDATE students
                                     SET password_reset_token = :password_reset_token,
                                         updated_at = :updated_at
                                   WHERE id = :student_id ");
      $pw_reset_stmt->bindValue(':password_reset_token', $password_reset_token, PDO::PARAM_STR);
      $pw_reset_stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
      $pw_reset_stmt->bindValue(':student_id', $account_holder['student_id'], PDO::PARAM_INT);
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
$smarty->display('student/reset_password.tpl');
?>