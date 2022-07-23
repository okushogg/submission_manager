<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

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
    $stmt = $db->prepare('select * from students where email=:email limit 1');
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $student_info = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($student_info && $student_info['is_active'] == 1) {
      if (password_verify($password, $student_info['password'])) {
        // ログイン成功
        session_regenerate_id();
        $_SESSION['auth']['login'] = true;
        $_SESSION['auth']['student_id'] = $student_info['id'];
        $_SESSION['auth']['last_name'] = $student_info['last_name'];
        $_SESSION['auth']['first_name'] = $student_info['first_name'];
        $_SESSION['auth']['student_image_id'] = $student_info['image_id'];
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
var_dump($_SESSION);
$smarty->caching = 0;
$smarty->display('student/log_in.tpl');
