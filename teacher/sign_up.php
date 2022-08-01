<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');
require_once('../private/set_up.php');
require('../private/error_check.php');
$smarty = new Smarty_submission_manager();

// header tittle
$title = "教員 登録ページ";
$smarty->assign('title', $title);

is_teacher_login();

if (isset($_GET['action']) && isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  $form = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'password' => '',
    'image_id' => '',
    'is_active' => true
  ];
}

$error = [];
$smarty->assign('error', $error);
$smarty->assign('form', $form);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // エラーチェック
  list($error, $form) = error_check($db, $this_year, $today, $form, "teachers");

  // エラーがなかった場合
  if (empty($error)) {
    $_SESSION['form'] = $form;

    // 画像のアップロード
    $image = $_FILES['image'];
    if ($image['name'] !== '') {
      $filename = date('Ymdhis') . '_' . $image['name'];
      if (!move_uploaded_file($image['tmp_name'], '../teacher_pictures/' . $filename)) {
        die('ファイルのアップロードに失敗しました');
      }
      $_SESSION['form']['image'] = $filename;
    } else {
      $_SESSION['form']['image'] = '';
    }

    header('Location: check.php');
    exit();
  }
  $smarty->assign('error', $error);
  $smarty->assign('form', $form);
}
$smarty->caching = 0;
$smarty->display('teacher/sign_up.tpl');
