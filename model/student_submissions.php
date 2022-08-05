<?php

class student_submission
{
  function get_recent_submissions($db, $student_id, $class_id)
  {
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
    $recent_submissions = $submission_stmt->fetchAll(PDO::FETCH_ASSOC);
    return $recent_submissions;
  }
}
