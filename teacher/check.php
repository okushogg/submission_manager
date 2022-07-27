<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

// 直接check.phpに飛ばないようにする
if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  header('Location: sign_up.php');
  exit();
}

// student/check.phpから飛ばないようにする。
if(isset($_SESSION['form']['student_num'])){
  header('Location: ../student/sign_up.php');
  unset($_SESSION['form']);
  exit();
}

$form = $_SESSION['form'];
$smarty->assign('form', $form);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 画像がある場合
  if ($form['image'] !== '') {
    $stmt = $db->prepare('insert into images(path) VALUES(?)');
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(1, $form['image'], PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $get_image_id = $db->prepare("select id from images where path = '" . $form['image'] . "'");
    $get_image_id->execute();
    $image_id_str = $get_image_id->fetch(PDO::FETCH_COLUMN);
    $image_id = intval($image_id_str);
    unset($stmt);
  } else {
    // 画像がない場合
    $image_id = $no_image_id;
  }

  // パスワードをDBに直接保管しない
  $password = password_hash($form['password'], PASSWORD_DEFAULT);
  $stmt = $db->prepare('insert into teachers(last_name, first_name, email, password, image_id) values(?, ?, ?, ?, ?)');
  if (!$stmt) {
    die($db->error);
  }
  $success = $stmt->execute(array($form['last_name'], $form['first_name'], $form['email'], $password, $image_id));
  if (!$success) {
    die($db->error);
  }
  $teacher_id = $db->lastInsertId();

  // セッションにteacherの情報を入れる
  $_SESSION['auth']['is_login'] = true;
  $_SESSION['auth']['teacher_id'] = $teacher_id;
  $_SESSION['auth']['last_name'] = $form['last_name'];
  $_SESSION['auth']['first_name'] = $form['first_name'];
  $_SESSION['auth']['teacher_image_id'] = $image_id;

  unset($_SESSION['form']);
  header('Location: home.php');
}

$smarty->caching = 0;
$smarty->display('teacher/check.tpl');
?>