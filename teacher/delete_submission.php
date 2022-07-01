<?php
session_start();
require('../dbconnect.php');
require('../libs.php');

// 現在の時刻
$current_time = bkk_time();

// submission_id
$submission_id = filter_input(INPUT_GET, 'submission_id', FILTER_SANITIZE_NUMBER_INT);

// teacherのid
$teacher_id = $_SESSION['teacher_id'];

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
$class_id = $submission_info['class_id'];

// submissionsを編集
  $stmt = $db->prepare("UPDATE submissions
                        SET is_deleted = 1,
                            teacher_id = :teacher_id,
                            updated_at = :updated_at
                      WHERE id = :submission_id");
  if (!$stmt) {
    die($db->error);
  }
  $stmt->bindValue(':teacher_id', $teacher_id, PDO::PARAM_INT);
  $stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
  $stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
  $success = $stmt->execute();
  if (!$success) {
    die($db->error);
  }
header("Location: index_submission.php?class_id={$class_id}");
exit();
