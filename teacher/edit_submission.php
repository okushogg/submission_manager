<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');
require('../private/error_check.php');
require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

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
$smarty->assign('form',$form);

// エラーの初期化
$error = [];
$smarty->assign('error',$error);

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
$pic_info = get_pic_info($db, $image_id);
$smarty->assign('pic_info', $pic_info);


// 教科一覧
$subjects_stmt = $db->prepare("SELECT id, name FROM subjects");
$subjects_stmt->execute();
$all_subjects = $subjects_stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('all_subjects', $all_subjects);

// 課題の情報を求める
$submission_stmt = $db->prepare("SELECT submissions.name, submissions.dead_line,
                                        subjects.id as subject_id,
                                        subjects.name as subject_name,
                                        submissions.class_id,
                                        classes.grade, classes.class
                                   FROM submissions
                                   LEFT JOIN subjects
                                   ON submissions.subject_id = subjects.id
                                   LEFT JOIN classes
                                   ON submissions.class_id = classes.id
                                   WHERE submissions.id = :submission_id");
if (!$submission_stmt) {
  die($db->error);
}
$submission_stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
$success = $submission_stmt->execute();
if (!$success) {
  die($db->error);
}
$submission_info = $submission_stmt->fetch(PDO::FETCH_ASSOC);
$smarty->assign('submission_info',$submission_info);
$class_id = $submission_info['class_id'];


//「課題内容を編集」をクリックしたら
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // エラーチェック
  list($error, $form) = error_check($db, $this_year, $today, $form, "teachers");

  // teacherのid
  $teacher_id = $form['teacher_id'];

  // 入力に問題がない場合
  if (empty($error)) {
    // submissionsを編集
    $stmt = $db->prepare("UPDATE submissions
                          SET name = :submission_name,
                              subject_id = :subject_id,
                              dead_line = :dead_line,
                              teacher_id = :teacher_id,
                              updated_at = :updated_at
                        WHERE id = :submission_id");
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindValue(':submission_name', $form['submission_name'], PDO::PARAM_STR);
    $stmt->bindValue(':subject_id', $form['subject_id'], PDO::PARAM_INT);
    $stmt->bindValue(':dead_line', $form['dead_line'], PDO::PARAM_STR);
    $stmt->bindValue(':teacher_id', $form['teacher_id'], PDO::PARAM_INT);
    $stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
    $stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    header("Location: index_submission.php?class_id={$class_id}");
    exit();
  }
  
  $smarty->assign('form',$form);
  $smarty->assign('error',$error);
}

$smarty->caching = 0;
$smarty->display('teacher/edit_submission.tpl');
?>