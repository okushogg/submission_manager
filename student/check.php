<?php
session_start();
require('../private/libs.php');

require_once('../private/set_up.php');
require_once('../model/students.php');
require_once('../model/classes.php');
require_once('../model/images.php');
require_once('../model/belongs.php');

$smarty = new Smarty_submission_manager();
$class = new classRoom();
$image = new image();
$belong = new belong();
$student = new student();

// header tittle
$title = "生徒 登録確認ページ";
$smarty->assign('title', $title);

// 直接check.phpに飛ばないようにする
if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  header('Location: sign_up.php');
  exit();
}

// class_idから学年クラスを表示する
$chosen_class = $class->get_chosen_class($form['class_id']);
$smarty->assign('chosen_class', $chosen_class);

// 登録内容を確定
$form = $_SESSION['form'];
$smarty->assign('form', $form);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 画像がある場合
  if ($form['image'] !== '') {
    $image_id = $image->pic_register($form['image']);
  } else {
    // 画像がない場合はlibsに指定した$no_image_idを使用
    $image_id = $no_image_id;
  }

  // パスワードをDBに直接保管しない
  $password = password_hash($form['password'], PASSWORD_DEFAULT);

  // 情報をテーブルに保存
  $student->register_new_student($form, $password, $image_id);

  // 作成したstudentsテーブルのレコードからstudent_idを求める
  $registered_student_info = $student->student_login($form['email']);
  $student_id = $registered_student_info['id'];

  // 所属クラスと出席番号の情報をbelongsテーブルに保存
  $belong->register_new_student_belongs($student_id, $form['class_id'], $form['student_num']);

  // セッションにstudentの情報を入れる
  $_SESSION['auth']['is_login'] = true;
  $_SESSION['auth']['student_id'] = $student_id;
  $_SESSION['auth']['last_name'] = $form['last_name'];
  $_SESSION['auth']['first_name'] = $form['first_name'];
  $_SESSION['auth']['student_image_id'] = $image_id;

  // セッション内のフォーム内容を破棄してstudent/home.phpへ
  unset($_SESSION['form']);
  header('Location: home.php');
}
$smarty->caching = 0;
$smarty->display('student/check.tpl');
