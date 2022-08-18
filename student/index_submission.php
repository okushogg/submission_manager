<?php
session_start();
require('../private/libs.php');

require_once('../private/set_up.php');
require_once('../model/students.php');
require_once('../model/images.php');
require_once('../model/belongs.php');
require_once('../model/student_submissions.php');
require_once("../model/subjects.php");

$smarty = new Smarty_submission_manager();
$student = new student();
$image = new image();
$belong = new belong();
$subject = new subject();
$student_submission = new student_submission();

// header tittle
$title = "生徒 課題一覧ページ";
$smarty->assign('title', $title);

// 選択した教科のidを求める
if (isset($_GET['subject_id'])) {
  $subject_id = $_GET['subject_id'];
} else {
  header('Location: home.php');
}
$smarty->assign('subject_id', $subject_id);


// class_idを取得
$class_id = $_GET['class_id'];
$smarty->assign('class_id', $class_id);

// ログイン情報がないとログインページへ移る
login_check($student_page);

// 生徒がログインしていた場合
$student_id = $_SESSION['auth']['student_id'];

// studentの情報を求める
$student_info = $student->get_student_info($student_id);
$smarty->assign('student_info', $student_info);

// 生徒の画像情報を取得
$student_pic_info = $image->get_pic_info($student_info['image_id']);
$smarty->assign('student_pic_info', $student_pic_info);


// クラスを求める
$chosen_class = $belong->get_class_student_num($student_id, $class_id);
$smarty->assign('chosen_class', $chosen_class);


// 教科一覧
$all_subjects = $subject->get_all_subjects();
$smarty->assign('all_subjects', $all_subjects);

// 生徒が持つ本年度の該当教科課題を求める
$submission_info = $student_submission->get_submission_with_subject($student_id, $class_id, $subject_id);
$smarty->assign('submission_info', $submission_info);

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
$smarty->display('student/index_submission.tpl');

?>