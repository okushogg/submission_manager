<?php

class student_submission extends database
{
  // DB接続
  function __construct()
  {
    parent::connect_db();
  }

  // 作成したsubmissionsレコードに紐付く該当クラス全生徒のstudent_submissionsレコードを作成
  function create_student_submission($submission_id, $student_id){
    $stmt = $this->pdo->prepare("INSERT INTO student_submissions(student_id,
                                                                     submission_id)
                                          VALUES (:student_id,
                                                  :submission_id)");
      if (!$stmt) {
        die($this->pdo->error);
      }
      $stmt->bindValue(':student_id', $student_id['student_id'], PDO::PARAM_INT);
      $stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
      $success = $stmt->execute();
      print_r($submission_id);
      if (!$success) {
        die($this->pdo->error);
      }
    }

  // 選択した強化の課題を取得する
  function get_submission_with_subject($student_id, $class_id, $subject_id)
  {
    $stmt = $this->pdo->prepare("SELECT student_submissions.id, submissions.name as submission_name, submissions.dead_line,
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
      die($this->pdo->error);
    }
    $stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->bindValue(':subject_id', $subject_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    if (!$success) {
      die($this->pdo->error);
    }
    $submission_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $submission_info;
  }


  // 前後1週間の課題を取得する
  function get_recent_submissions($student_id, $class_id)
  {
    $a_week_ago = date("Y-m-d", strtotime("-1 week"));
    $a_week_later = date("Y-m-d", strtotime("+1 week"));
    $submission_stmt = $this->pdo->prepare("SELECT student_submissions.id, submissions.name as submission_name, submissions.dead_line,
                                            COALESCE(student_submissions.approved_date,'-') as approved_date,
                                            COALESCE(student_submissions.score, '-') as score, submissions.subject_id
                                       FROM student_submissions
                                  LEFT JOIN submissions
                                         ON student_submissions.submission_id = submissions.id
                                      WHERE student_submissions.student_id = :student_id
                                        AND submissions.class_id = :class_id
                                        AND submissions.is_deleted = 0;
                                        AND submissions.dead_line BETWEEN :a_week_ago and :a_week_later");
    if (!$submission_stmt) {
      die($this->pdo->error);
    }
    $submission_stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
    $submission_stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
    $submission_stmt->bindValue(':a_week_ago', $a_week_ago, PDO::PARAM_STR);
    $submission_stmt->bindValue(':a_week_later', $a_week_later, PDO::PARAM_STR);
    $submission_success = $submission_stmt->execute();
    if (!$submission_success) {
      die($this->pdo->error);
    }
    $recent_submissions = $submission_stmt->fetchAll(PDO::FETCH_ASSOC);
    return $recent_submissions;
  }

  // 課題が与えられた全ての生徒を求める
  function get_all_students_who_have_submission($submission_id, $class_id)
  {
    $student_stmt = $this->pdo->prepare("SELECT student_submissions.id as student_submissions_id,
                                         student_submissions.student_id as student_id,
                                         COALESCE(student_submissions.approved_date,'-') as approved_date,
                                         COALESCE(student_submissions.score,NULL) as score,
                                         submissions.dead_line as dead_line,
                                         students.first_name, students.last_name
                                    FROM student_submissions
                               LEFT JOIN students
                                      ON student_submissions.student_id = students.id
                               LEFT JOIN submissions
                                      ON student_submissions.submission_id = submissions.id
                                   WHERE student_submissions.submission_id = :submission_id
                                     AND submissions.class_id = :class_id
                                     AND students.is_active = 1;");
    if (!$student_stmt) {
      die($this->pdo->error);
    }
    $student_stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
    $student_stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
    $student_success = $student_stmt->execute();
    if (!$student_success) {
      die($this->pdo->error);
    }
    $students_who_have_submission = $student_stmt->fetchAll(PDO::FETCH_ASSOC);
    return $students_who_have_submission;
  }

  // 課題の評価を更新する
  function update_submission_score($score_array, $h_id, $today, $current_time){
    $homework_stmt = $this->pdo->prepare("UPDATE student_submissions
                                      SET score = :score,
                                          approved_date = :approved_date,
                                          updated_at = :updated_at
                                    WHERE id = :student_submissions_id");
      if (!$homework_stmt) {
        die($this->pdo->error);
      }
      $homework_stmt->bindValue(':score', $score_array[$h_id], PDO::PARAM_INT);
      $homework_stmt->bindValue(':approved_date', $today, PDO::PARAM_STR);
      $homework_stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
      $homework_stmt->bindValue(':student_submissions_id', $h_id, PDO::PARAM_INT);
      $homework_success = $homework_stmt->execute();
      if (!$homework_success) {
        die($this->pdo->error);
      }
  }
}
