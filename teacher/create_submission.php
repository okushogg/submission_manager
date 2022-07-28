<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');
require('../private/error_check.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

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
login_check();

// 教員のログインか確認
is_teacher_login();

// セッション内の情報
$teacher_info = $_SESSION['auth'];
$smarty->assign('teacher_info', $teacher_info);

// 教員がログインしていた場合
$teacher_id = $_SESSION['auth']['teacher_id'];
$image_id = $_SESSION['auth']['teacher_image_id'];

// 画像の情報を取得
$pic_info = get_pic_info($db, $image_id);
$smarty->assign('pic_info', $pic_info);

// 該当年度の年度のクラスを取得する
$classes_stmt = $db->prepare("select id, year, grade, class from classes where year=:year");
$classes_stmt->bindParam(':year', $this_year, PDO::PARAM_STR);
$classes_stmt->execute();
$classes_info = $classes_stmt->fetchAll(PDO::FETCH_ASSOC);
$cnt = count($classes_info);
$json_classes_info = json_encode($classes_info);
$smarty->assign('classes_info', $classes_info);

// 教科一覧
$subjects_stmt = $db->prepare("SELECT id, name FROM subjects");
$subjects_stmt->execute();
$all_subjects = $subjects_stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('all_subjects', $all_subjects);


//「課題を作成する」をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // エラーチェック
  list($error, $form) = error_check($db, $this_year, $today, $form);

  // 入力に問題がない場合
  if (empty($error)) {
    $_SESSION['form'] = $form;
    $class_id = intval($form['class_id']);

    // 指定されたclass_idを持つ全てのstudent_idを求める(除籍済を除く)
    $student_stmt = $db->prepare("SELECT b.student_id as student_id, b.student_num as student_num
                                  FROM belongs AS b
                                  INNER JOIN students AS s
                                  ON b.student_id = s.id
                                  WHERE class_id = :class_id AND s.is_active = 1
                                  ORDER BY b.student_num");
    $student_stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
    $student_success = $student_stmt->execute();
    if (!$student_success) {
      die($db->error);
    }
    $all_student_id = $student_stmt->fetchAll(PDO::FETCH_ASSOC);

    // submissionsレコードを作成
    $stmt = $db->prepare("INSERT INTO submissions(name,
                                                    class_id,
                                                    subject_id,
                                                    dead_line,
                                                    teacher_id)
                                            VALUES(:name,
                                                   :class_id,
                                                   :subject_id,
                                                   :dead_line,
                                                   :teacher_id)");
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindValue(':name', $form['submission_name'], PDO::PARAM_STR);
    $stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->bindValue(':subject_id', $form['subject_id'], PDO::PARAM_INT);
    $stmt->bindValue(':dead_line', $form['dead_line'], PDO::PARAM_STR);
    $stmt->bindValue(':teacher_id', $teacher_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }

    // 作成したsubmissionsレコードに紐付く該当クラス全生徒のstudent_submissionsレコードを作成
    $submission_id = $db->lastInsertId();
    foreach ($all_student_id as $student_id) {
      $submission_stmt = $db->prepare("INSERT INTO student_submissions(student_id,
                                                                         submission_id)
                                                                  VALUES(:student_id,
                                                                         :submission_id)");
      if (!$submission_stmt) {
        die($db->error);
      }
      $submission_stmt->bindValue(':student_id', $student_id['student_id'], PDO::PARAM_INT);
      $submission_stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
      $submission_success = $submission_stmt->execute();
      if (!$submission_success) {
        die($db->error);
      }
    }
    header('Location: home.php');
    exit();
  }
  $smarty->assign('form', $form);
  $smarty->assign('error', $error);
}
$smarty->caching = 0;
$smarty->display('teacher/create_submission.tpl');
