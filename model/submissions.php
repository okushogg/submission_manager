<?php

class submission
{
  // submission_idから課題の情報を求める
  function get_submission_info($db, $submission_id)
  {
    $submission_stmt = $db->prepare("SELECT submissions.name, submissions.dead_line,
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
    return $submission_info;
  }


  // class_idからそのクラスが持つ全ての課題を求める
  function get_class_all_submissions($db, $class_id)
  {
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
    $all_submissions_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $all_submissions_info;
  }
}
