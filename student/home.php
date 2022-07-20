<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

// ログイン情報がないとログインページへ移る
login_check();

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

// teacherがstudent/を閲覧した場合
if (isset($_GET['student_id'])) {
  $_SESSION['auth']['student_id'] = $_GET['student_id'];
}

// studentの情報を求める
$student_stmt = $db->prepare("SELECT first_name as student_first_name,
                                     last_name as student_last_name,
                                     image_id, is_active
                              FROM students
                              WHERE id=:student_id");
$student_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$student_stmt->execute();
$student_info = $student_stmt->fetch(PDO::FETCH_ASSOC);
$smarty->assign('student_info', $student_info);


// 生徒の画像情報を取得
$stmt = $db->prepare("select path from images where id=:image_id");
if (!$stmt) {
  die($db->error);
}
$stmt->bindValue(':image_id', $student_info['image_id'], PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$student_pic_info = $stmt->fetch(PDO::FETCH_ASSOC);
$smarty->assign('student_pic_info', $student_pic_info);

// 本年度の所属クラスを求める
$this_year_class_stmt = $db->prepare("SELECT belongs.id, belongs.class_id, belongs.student_num as student_num,
                                             classes.class as class, classes.grade as grade
                                      FROM belongs
                                      LEFT JOIN classes
                                      ON belongs.class_id = classes.id
                                      WHERE belongs.student_id = :student_id
                                        AND classes.year = :year");
$this_year_class_stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
$this_year_class_stmt->bindValue(':year', $form['year'], PDO::PARAM_INT);
$this_year_class_stmt->execute();
$this_year_class = $this_year_class_stmt->fetch(PDO::FETCH_ASSOC);
$smarty->assign('this_year_class', $this_year_class);
if ($this_year_class) {
  $class_id = $this_year_class['class_id'];
} else {
  $class_id = null;
}
$smarty->assign('class_id', $class_id);


//生徒が所属していたクラスを求める
$classes_stmt = $db->prepare("SELECT c.grade, b.id, b.class_id, c.grade, c.year,  c.class, b.student_num
                              FROM belongs AS b INNER JOIN classes AS c ON b.class_id = c.id
                              WHERE student_id=:student_id
                              ORDER BY c.year ASC");
$classes_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$classes_stmt->execute();
$belonged_classes = $classes_stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
$smarty->assign('belonged_classes', $belonged_classes);


// 教科一覧
$subjects_stmt = $db->prepare("SELECT id, id as subject_id, name FROM subjects");
$subjects_stmt->execute();
$all_subjects = $subjects_stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
$smarty->assign('all_subjects', $all_subjects);

// 生徒が持つ課題を求める（提出期限の前後1週間のもの）
$a_week_ago = date("Y-m-d", strtotime("-1 week"));
$a_week_later = date("Y-m-d", strtotime("+1 week"));
$submission_stmt = $db->prepare("SELECT student_submissions.id, submissions.name as submission_name, submissions.dead_line,
                             COALESCE(student_submissions.approved_date,'-') as approved_date,
                             COALESCE(student_submissions.score, '-') as score, submissions.subject_id
                        FROM student_submissions
                   LEFT JOIN submissions
                          ON student_submissions.submission_id = submissions.id
                       WHERE student_submissions.student_id = :student_id
                         AND submissions.class_id = :class_id
                         AND submissions.is_deleted = 0;
                         AND submissions.dead_line BETWEEN :a_week_ago AND :a_week_later");
if (!$submission_stmt) {
  die($db->error);
}
$submission_stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
$submission_stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
$submission_stmt->bindValue(':a_week_ago', $a_week_ago, PDO::PARAM_STR);
$submission_stmt->bindValue(':a_week_later', $a_week_later, PDO::PARAM_STR);
$submission_success = $submission_stmt->execute();
if (!$submission_success) {
  die($db->error);
}
$submission_info = $submission_stmt->fetchAll(PDO::FETCH_ASSOC);
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
$smarty->display('student/home.tpl');
?>