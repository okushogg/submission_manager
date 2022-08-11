<?php

class submission
{
  function get_class_submission($db, $class_id)
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
    $submission_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $submission_info;
  }
}
