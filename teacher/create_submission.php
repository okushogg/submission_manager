<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');
require('../private/error_check.php');

require_once('../private/set_up.php');
require_once('../model/teachers.php');
require_once('../model/images.php');
require_once('../model/belongs.php');
require_once('../model/classes.php');
require_once('../model/subjects.php');
require_once('../model/submissions.php');
require_once('../model/student_submissions.php');

$smarty = new Smarty_submission_manager();
$teacher = new teacher();
$image = new image();
$belong = new belong();
$class = new classRoom();
$subject = new subject();
$submission = new submission();
$student_submission = new student_submission();

// header tittle
$title = "教員課題確認ページ";
$smarty->assign('title', $title);

// フォームの中身を確認、内容がなければ初期化
if (isset($_GET['action']) && isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  $form = [
    'submission_name' => '',
    'class_id' => '',
    'subject_id' => '',
    'dead_line' => '',
    'teacher_id' => $_SESSION['auth']['teacher_id'],
  ];
}
$smarty->assign('form', $form);


// エラーの初期化
$error = [];
$smarty->assign('error', $error);

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
$pic_info = $image->get_pic_info($image_id);
$smarty->assign('pic_info', $pic_info);

// 該当年度の年度のクラスを取得する
$classes_info = $class->get_this_year_classes($this_year);
// $cnt = count($classes_info);
// $json_classes_info = json_encode($classes_info);
$smarty->assign('classes_info', $classes_info);

// 教科一覧
$all_subjects = $subject->get_all_subjects();
$smarty->assign('all_subjects', $all_subjects);


//「課題を作成する」をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // エラーチェック
  list($error, $form) = error_check($this_year, $today, $form, "teachers");

  // 入力に問題がない場合
  if (empty($error)) {
    $_SESSION['form'] = $form;
    // 作成したsubmissionsレコードに紐付く該当クラス全生徒のstudent_submissionsレコードを作成
    $result = $student_submission->create_student_submission($form);
    if ($result) {
      header('Location: home.php');
      exit();
    }
  }
  $smarty->assign('form', $form);
  $smarty->assign('error', $error);
}
$smarty->caching = 0;
$smarty->display('teacher/create_submission.tpl');
