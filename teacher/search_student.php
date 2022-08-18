<?php
session_start();
require('../private/libs.php');

require_once('../private/set_up.php');
require_once('../model/images.php');
require_once('../model/belongs.php');
require_once('../model/classes.php');

$smarty = new Smarty_submission_manager();
$image = new image();
$belong = new belong();
$class = new classRoom();

// header tittle
$title = "教員 生徒検索ページ";
$smarty->assign('title', $title);

// セッション内の情報
$teacher_info = $_SESSION['auth'];
$smarty->assign('teacher_info', $teacher_info);

$smarty->assign('_POST', $_POST);

$form = [
  "last_name" => '',
  "first_name" => '',
  "year" => '',
  "grade" => '',
  "class" => '',
  "is_active" => ''
];
$smarty->assign('form', $form);

$grades = [
  "-" => 0,
  "1" => 1,
  "2" => 2,
  "3" => 3
];
$smarty->assign('grades', $grades);

$classes = [
  "-" => '-',
  "A" => 'A',
  "B" => 'B',
  "C" => 'C'
];
$smarty->assign('classes', $classes);


// ログイン情報がないとログインページへ移る
login_check($teacher_page);

// 教員のログインか確認
$teacher_id = is_teacher_login();

// 教員がログインしていた場合
$image_id = $_SESSION['auth']['teacher_image_id'];

// 画像の情報を取得
$pic_info = $image->get_pic_info($image_id);
$smarty->assign('pic_info', $pic_info);

// 登録されている年度を全て取得
$all_years = $class->get_years();
$smarty->assign('all_years', $all_years);

$student_search_result = [];
$smarty->assign('student_search_result', $student_search_result);

// 検索ボタン押下時
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $form['year'] = filter_input(INPUT_POST, 'year');
  $form['grade'] = filter_input(INPUT_POST, 'grade');
  $form['class'] = filter_input(INPUT_POST, 'class');
  $form['is_active'] = filter_input(INPUT_POST, 'is_active');
  $form['last_name'] = filter_input(INPUT_POST, 'last_name');
  $form['first_name'] = filter_input(INPUT_POST, 'first_name');

  $student_search_result = $belong->search_students($form);
  $smarty->assign('student_search_result', $student_search_result);
  $smarty->assign('form', $form);
}
$smarty->caching = 0;
$smarty->display('teacher/search_student.tpl');
