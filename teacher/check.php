<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
require_once('../model/teachers.php');
require_once('../model/images.php');

$smarty = new Smarty_submission_manager();
$teacher = new teacher();
$image = new image();


// header tittle
$title = "教員登録確認ページ";
$smarty->assign('title', $title);

is_teacher_login();

// 直接check.phpに飛ばないようにする
if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  header('Location: sign_up.php');
  exit();
}

$form = $_SESSION['form'];
$smarty->assign('form', $form);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 画像がある場合
  if ($form['image'] !== '') {
    $image_id = $image->pic_register($db, $file_name);
  } else {
    // 画像がない場合
    $image_id = $no_image_id;
  }

  // パスワードをDBに直接保管しない
  $password = password_hash($form['password'], PASSWORD_DEFAULT);
  $teacher->register_new_teacher($db, $form, $password, $image_id);
  $teacher_info = $teacher->teacher_login($db, $form['email']);

  // セッションにteacherの情報を入れる
  $_SESSION['auth']['is_login'] = true;
  $_SESSION['auth']['teacher_id'] = $teacher_info['id'];
  $_SESSION['auth']['last_name'] = $teacher_info['last_name'];
  $_SESSION['auth']['first_name'] = $teacher_info['first_name'];
  $_SESSION['auth']['teacher_image_id'] = $teacher_info['image_id'];

  unset($_SESSION['form']);
  header('Location: home.php');
}

$smarty->caching = 0;
$smarty->display('teacher/check.tpl');
?>