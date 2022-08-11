<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');
require('../private/error_check.php');

require_once('../private/set_up.php');
require_once('../model/teachers.php');
require_once('../model/images.php');
require_once('../model/belongs.php');
require_once('../model/subjects.php');
require_once('../model/submissions.php');
require_once('../model/student_submissions.php');

$smarty = new Smarty_submission_manager();
$teacher = new teacher();
$image = new image();
$belong = new belong();
$subject = new subject();
$submission = new submission();
$student_submission = new student_submission();

// header tittle
$title = "教員 課題編集ページ";
$smarty->assign('title', $title);

// 現在の時刻
$current_time = bkk_time();

// フォームの中身を初期化
$form = [
  'submission_name' => '',
  'subject_id' => '',
  'dead_line' => '',
  'teacher_id' => $_SESSION['auth']['teacher_id'],
];
$smarty->assign('form', $form);

// エラーの初期化
$error = [];
$smarty->assign('error', $error);

// submission_id
$submission_id = filter_input(INPUT_GET, 'submission_id', FILTER_SANITIZE_NUMBER_INT);
$smarty->assign('submission_id', $submission_id);

// 今日の日付
$today = date('Y-m-d');

// ログイン情報がないとログインページへ移る
login_check($teacher_page);

// 教員のログインか確認
$teacher_id = is_teacher_login();

// セッション内の情報
$teacher_info = $_SESSION['auth'];
$smarty->assign('teacher_info', $teacher_info);

// 教員がログインしていた場合
$image_id = $_SESSION['auth']['teacher_image_id'];

// 画像の情報を取得
$pic_info = $image->get_pic_info($db, $image_id);
$smarty->assign('pic_info', $pic_info);


// 教科一覧
$all_subjects = $subject->get_all_subjects($db);
$smarty->assign('all_subjects', $all_subjects);

// 課題の情報を求める
$submission_info = $submission->get_submission_info($db, $submission_id);
$smarty->assign('submission_info', $submission_info);
$class_id = $submission_info['class_id'];


//「課題内容を編集」をクリックしたら
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // エラーチェック
  list($error, $form) = error_check($db, $this_year, $today, $form, "teachers");

  // 入力に問題がない場合
  if (empty($error)) {
    // submissionsを編集
    $submission->edit_submission($db, $form, $submission_id, $current_time);
    header("Location: index_submission.php?class_id={$class_id}");
    exit();
  }

  $smarty->assign('form', $form);
  $smarty->assign('error', $error);
}
var_dump($submission_info);

$smarty->caching = 0;
$smarty->display('teacher/edit_submission.tpl');
