<?php
session_start();
require('../private/libs.php');

require_once('../private/set_up.php');
require_once('../model/teachers.php');

$smarty = new Smarty_submission_manager();
$teacher = new teacher();

// header tittle
$title = "教員 ログインページ";
$smarty->assign('title', $title);

is_teacher_login();

$error = [];
$smarty->assign('error', $error);

$email = '';
$password = '';
$smarty->assign('email', $email);
$smarty->assign('password', $password);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password');
  $smarty->assign('email', $email);
  $smarty->assign('password', $password);
  if ($email === '' || $password === '') {
    $error['login'] = 'blank';
  } else {
    //  ログイン情報チェック
    $teacher_info = $teacher->teacher_login($email);
    if ($teacher_info && $teacher_info['is_active'] == 1) {
      if (password_verify($password, $teacher_info['password'])) {
        // ログイン成功
        session_regenerate_id();
        $_SESSION['auth']['is_login'] = true;
        $_SESSION['auth']['teacher_id'] = $teacher_info['id'];
        $_SESSION['auth']['last_name'] = $teacher_info['last_name'];
        $_SESSION['auth']['first_name'] = $teacher_info['first_name'];
        $_SESSION['auth']['teacher_image_id'] = $teacher_info['image_id'];
        header('Location: home.php');
        exit();
      } else {
        // ログイン失敗
        $error['login'] = 'failed';
      }
    } else {
      $error['login'] = 'failed';
    }
  }
  $smarty->assign('error', $error);
}

$smarty->caching = 0;
$smarty->display('teacher/log_in.tpl');
