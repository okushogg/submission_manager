<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
require_once('../model/students.php');
require_once('../model/images.php');
require_once('../model/belongs.php');
require_once('../model/subjects.php');
require_once('../model/student_submissions.php');


$smarty = new Smarty_submission_manager();
$student = new student();
$picture = new image();
$belong = new belong();
$subject = new subject();
$student_submission = new student_submission();

// header tittle
$title = "生徒　ホーム";
$smarty->assign('title', $title);

// ログイン情報がないとログインページへ移る
login_check($student_page);

// teacherがstudentを閲覧した場合
if (isset($_GET['student_id']) && isset($_SESSION['auth']['teacher_id'])) {
  $_SESSION['auth']['student_id'] = $_GET['student_id'];
}

// 生徒がログインしていた場合
$student_id = $_SESSION['auth']['student_id'];

// index_submissionから戻ってきた際に年度の情報を引き継ぐ
if(isset($_GET['year']) && $_GET['year']<= $this_year){
  $form = [ 'year' => $_GET['year']];
} else {
  $form = [
  'year' => $this_year
];
}
$smarty->assign('this_year', $this_year);
$smarty->assign('form', $form);

// 変更ボタン押下時
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $form['year'] = filter_input(INPUT_POST, 'year');
}

// studentの情報を求める
$student_info = $student->get_student_info($student_id);
$smarty->assign('student_info', $student_info);


// 生徒の画像情報を取得
if($student_info){
  $student_pic_info = $picture->get_pic_info($student_info['image_id']);
  $smarty->assign('student_pic_info', $student_pic_info);
}

// 本年度の所属クラスを取得する
$chosen_year_class = $belong->get_chosen_year_class($student_id, $form['year']);
$smarty->assign('chosen_year_class', $chosen_year_class);
if ($chosen_year_class) {
  $class_id = $chosen_year_class['class_id'];
} else {
  $class_id = null;
}
$smarty->assign('class_id', $class_id);


//生徒が所属していたクラスを求める
$all_belonged_classes = $belong->get_all_belonged_classes($student_id);
$smarty->assign('all_belonged_classes', $all_belonged_classes);


// 教科一覧
$all_subjects = $subject->get_all_subjects();
$smarty->assign('all_subjects', $all_subjects);

// 生徒が持つ課題を求める（提出期限の前後1週間のもの）
$recent_submissions = $student_submission->get_recent_submissions($student_id, $class_id);
$smarty->assign('recent_submissions', $recent_submissions);

// scoreの値
$scoreList = array(
  '-' => "-",
  3 => "A",
  2 => "B",
  1 => "C",
  0 => "未提出"
);
$smarty->assign('scoreList', $scoreList);

$smarty->caching = 0;
$smarty->display('student/home.tpl');
?>