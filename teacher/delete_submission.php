<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../model/submissions.php');

$submission = new submission();

// 現在の時刻
$current_time = bkk_time();

// submission_id
$submission_id = filter_input(INPUT_GET, 'submission_id', FILTER_SANITIZE_NUMBER_INT);

// teacherのid
$teacher_id = is_teacher_login();

// 課題の情報を求める
$submission_info = $submission->get_submission_info($submission_id);
$class_id = $submission_info['class_id'];

// submissionsを削除
$submission->delete_submission($submission_id, $teacher_id, $current_time);
header("Location: index_submission.php?class_id={$class_id}");
exit();
