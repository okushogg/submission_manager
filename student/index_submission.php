<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

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
login_check();

// 生徒がログインしていた場合
$student_id = $_SESSION['auth']['student_id'];

// studentの情報を求める
$student_info = get_student_info($db, $student_id);
$smarty->assign('student_info', $student_info);

// 生徒の画像情報を取得
$student_pic_info = get_pic_info($db,$student_info['image_id']);
$smarty->assign('student_pic_info', $student_pic_info);


// クラスを求める
$class_stmt = $db->prepare("SELECT belongs.id, belongs.class_id, belongs.student_num as student_num,
                                   classes.year as year, classes.class as class, classes.grade as grade
                                      FROM belongs
                                      LEFT JOIN classes
                                      ON belongs.class_id = classes.id
                                      WHERE belongs.student_id = $student_id
                                        AND belongs.class_id = $class_id");
$class_stmt->execute();
$chosen_class = $class_stmt->fetch(PDO::FETCH_ASSOC);
$smarty->assign('chosen_class', $chosen_class);


// 教科一覧
$subjects_stmt = $db->prepare("SELECT id, name FROM subjects");
$subjects_stmt->execute();
$all_subjects = $subjects_stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
$smarty->assign('all_subjects', $all_subjects);

// 生徒が持つ本年度の該当教科課題を求める
$stmt = $db->prepare("SELECT student_submissions.id, submissions.name as submission_name, submissions.dead_line,
                             COALESCE(student_submissions.approved_date,'-') as approved_date,
                             COALESCE(student_submissions.score, '-') as score
                        FROM student_submissions
                   LEFT JOIN submissions
                          ON student_submissions.submission_id = submissions.id
                       WHERE student_submissions.student_id = :student_id
                         AND submissions.class_id = :class_id
                         AND submissions.subject_id = :subject_id
                         AND submissions.is_deleted = 0");
if (!$stmt) {
  die($db->error);
}
$stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
$stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
$stmt->bindValue(':subject_id', $subject_id, PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$submission_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
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