<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
require_once('../model/teachers.php');
require_once('../model/images.php');
require_once('../model/classes.php');


$smarty = new Smarty_submission_manager();
$teacher = new teacher();
$image = new image();
$class = new ClassRoom();

$form = [
  "last_name" => '',
  "first_name" => '',
  "year" => '',
  "grade" => '',
  "class" => ''
];

// header tittle
$title = "教員ホーム";
$smarty->assign('title', $title);

// セッション内の情報
$teacher_info = $_SESSION['auth'];
$smarty->assign('teacher_info', $teacher_info);


// ログイン情報がないとログインページへ移る
login_check($teacher_page);

// 教員のログインか確認
$teacher_id = is_teacher_login();

// 教員がログインしていた場合
$image_id = $_SESSION['auth']['teacher_image_id'];

// 画像の情報を取得
$pic_info = $image->get_pic_info($db, $image_id);
$smarty->assign('pic_info', $pic_info);


// 今年度のクラスを取得する
$classes_info = $class->get_this_year_classes($db, $this_year);
$smarty->assign('classes_info', $classes_info);


// 学年ごとにクラスのデータを配列で取得
$classes_array = get_classes($classes_info);
$smarty->assign('classes_array', $classes_array);




$smarty->caching = 0;
$smarty->display('teacher/home.tpl');
?>