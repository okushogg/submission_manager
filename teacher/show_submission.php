<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
require_once('../model/teachers.php');
require_once('../model/images.php');
require_once('../model/belongs.php');
require_once('../model/classes.php');
require_once('../model/submissions.php');
require_once('../model/student_submissions.php');

$smarty = new Smarty_submission_manager();
$teacher = new teacher();
$image = new image();
$belong = new belong();
$class = new classRoom();
$submission = new submission();
$student_submission = new student_submission();

// header tittle
$title = "教員 課題詳細ページ";
$smarty->assign('title', $title);

// フォームの初期化
$form = [
  array(
    'student_submissions_id' => 'score'
  )
];

// submission_id
$submission_id = filter_input(INPUT_GET, 'submission_id', FILTER_SANITIZE_NUMBER_INT);
$smarty->assign('submission_id', $submission_id);

// 今日の日付
$today = date('Y-m-d');
$smarty->assign('today',$today);

// 現在のバンコクの時刻
$current_time = bkk_time();

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
$pic_info = $image->get_pic_info($image_id);
$smarty->assign('pic_info', $pic_info);

// 課題の情報を求める
$submission_info = $submission->get_submission_info($submission_id);
$smarty->assign('submission_info', $submission_info);
$class_id = $submission_info['class_id'];

// 該当の課題が与えられた全ての生徒を求める
$students_who_have_submission = $student_submission->get_all_students_who_have_submission($submission_id, $class_id);
$smarty->assign('students_who_have_submission', $students_who_have_submission);

// 課題が与えられた生徒のclass_idからstudent_numを求める
$student_num_array = $belong->get_student_num_from_class_id($class_id);
$smarty->assign('student_num_array', $student_num_array);

// scoreの値
$scoreList = array(
  "-" => null,
  "A" => 3,
  "B" => 2,
  "C" => 1,
  "未提出" => 0
);
$smarty->assign('scoreList', $scoreList);

// 評価を更新をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($students_who_have_submission as $homework) {
    $score_array = $_POST['score'];
    $h_id = $homework['student_submissions_id'];
    if ($homework['score'] !=  $score_array[$h_id]) {
      $student_submission->update_submission_score($score_array, $h_id, $today, $current_time);
    }
  }
  header("Location: show_submission.php?submission_id={$submission_id}");
  exit();
}

$smarty->caching = 0;
$smarty->display('teacher/show_submission.tpl');
?>