<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

// セッション内の情報
$teacher_info = $_SESSION['auth'];
$smarty->assign('teacher_info', $teacher_info);

// class_id
$class_id = filter_input(INPUT_GET, 'class_id', FILTER_SANITIZE_NUMBER_INT);

// 今日の日付
$today = date('Y-m-d');

// ログイン情報がないとログインページへ移る
login_check();

// 教員のログインか確認
is_teacher_login();

// 教員がログインしていた場合
$teacher_id = $_SESSION['auth']['teacher_id'];
$image_id = $_SESSION['auth']['teacher_image_id'];

// 画像の情報を取得
$stmt = $db->prepare("select path from images where id=:id");
if (!$stmt) {
  die($db->error);
}
$stmt->bindParam(':id', $image_id, PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$pic_info = $stmt->fetch(PDO::FETCH_ASSOC);
$smarty->assign('pic_info', $pic_info);

// 該当クラスの課題を求める
$stmt = $db->prepare("SELECT submissions.id, submissions.name as submission_name, submissions.dead_line,
                             subjects.name as subject_name, teachers.first_name, teachers.last_name
                      FROM submissions
                      LEFT JOIN subjects
                      ON submissions.subject_id = subjects.id
                      LEFT JOIN teachers
                      ON submissions.teacher_id = teachers.id
                      WHERE submissions.class_id = :class_id
                      AND is_deleted = 0
                      ORDER BY id DESC");
if (!$stmt) {
  die($db->error);
}
$stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$submission_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('submission_info', $submission_info);

// クラスの情報を求める
$class_stmt = $db->prepare("SELECT id as class_id, year, grade, class
                            FROM classes
                            WHERE id = :class_id");
$class_stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
$class_success = $class_stmt->execute();
if (!$class_success){
  die($db->error);
}
$class_info = $class_stmt->fetch(PDO::FETCH_ASSOC);
$smarty->assign('class_info', $class_info);

$smarty->caching = 0;
$smarty->display('teacher/index_submission.tpl');

?>