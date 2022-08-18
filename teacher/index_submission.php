<?php
session_start();
require('../private/libs.php');

require_once('../private/set_up.php');
require_once('../model/images.php');
require_once('../model/classes.php');
require_once('../model/submissions.php');

$smarty = new Smarty_submission_manager();
$image = new image();
$class = new classRoom();
$submission = new submission();


// header tittle
$title = "教員 課題一覧";
$smarty->assign('title', $title);

// セッション内の情報
$teacher_info = $_SESSION['auth'];
$smarty->assign('teacher_info', $teacher_info);

// class_id
$class_id = filter_input(INPUT_GET, 'class_id', FILTER_SANITIZE_NUMBER_INT);

// 今日の日付
$today = date('Y-m-d');

// ログイン情報がないとログインページへ移る
login_check($teacher_page);

// 教員のログインか確認
$teacher_id = is_teacher_login();

// 教員がログインしていた場合
$image_id = $_SESSION['auth']['teacher_image_id'];

// 画像の情報を取得
$pic_info = $image->get_pic_info($image_id);
$smarty->assign('pic_info', $pic_info);

// 該当クラスの課題を求める
$submission_info = $submission->get_class_all_submissions($class_id);
$smarty->assign('submission_info', $submission_info);

// クラスの情報を求める
$class_info = $class->get_chosen_class($class_id);
$smarty->assign('class_info', $class_info);

$smarty->caching = 0;
$smarty->display('teacher/index_submission.tpl');

?>