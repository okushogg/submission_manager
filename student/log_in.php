<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
require_once('../model/students.php');

$smarty = new Smarty_submission_manager();
$student = new student();

// header tittle
$title = "生徒ログインページ";
$smarty->assign('title', $title);

$error = [];
$email = '';
$password = '';
$smarty->assign('email', $email);
$smarty->assign('password', $password);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password');
  if ($email === '' || $password === '') {
    $error['login'] = 'blank';
  } else {
    //  ログイン情報チェック
    $login_student = $student->student_login($db, $email);
    if ($login_student && $login_student['is_active'] == 1) {
      if (password_verify($password, $login_student['password'])) {
        // ログイン成功
        session_regenerate_id();
        $_SESSION['auth']['is_login'] = true;
        $_SESSION['auth']['student_id'] = $login_student['id'];
        $_SESSION['auth']['last_name'] = $login_student['last_name'];
        $_SESSION['auth']['first_name'] = $login_student['first_name'];
        $_SESSION['auth']['student_image_id'] = $login_student['image_id'];
        header('Location: home.php');
        exit();
      } else {
        // ログイン失敗
        $error['login'] = 'failed';
      }
    } else {
      // ログイン失敗
      $error['login'] = 'failed';
    }
  }
  $smarty->assign('email', $email);
  $smarty->assign('password', $password);
  $smarty->assign('error', $error);
}

$smarty->caching = 0;
$smarty->display('student/log_in.tpl');
