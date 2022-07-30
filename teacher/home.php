<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

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
is_teacher_login();

// 教員がログインしていた場合
$teacher_id = $_SESSION['auth']['teacher_id'];
$image_id = $_SESSION['auth']['teacher_image_id'];

// 画像の情報を取得
$pic_info = get_pic_info($db, $image_id);
$smarty->assign('pic_info', $pic_info);


// 今年度のクラスを取得する
$classes_stmt = $db->prepare("select id, year, grade, class from classes where year=:year");
$classes_stmt->bindParam(':year', $this_year, PDO::PARAM_STR);
$classes_stmt->execute();
$classes_info = $classes_stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('classes_info', $classes_info);
$cnt = count($classes_info);

// 学年ごとにクラスのデータを配列で取得
$classes_array = get_classes($classes_info);
$smarty->assign('classes_array', $classes_array);




$smarty->caching = 0;
$smarty->display('teacher/home.tpl');
?>