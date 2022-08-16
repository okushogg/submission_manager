<?php

class submission extends database
{
  // DB接続
  function __construct()
  {
    parent::connect_db();
  }

  // submission_idから課題の情報を求める
  function get_submission_info($submission_id)
  {
    $submission_stmt = $this->pdo->prepare("SELECT submissions.name, submissions.dead_line,
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
      die($this->pdo->error);
    }
    $submission_stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
    $success = $submission_stmt->execute();
    if (!$success) {
      die($this->pdo->error);
    }
    $submission_info = $submission_stmt->fetch(PDO::FETCH_ASSOC);
    return $submission_info;
  }


  // class_idからそのクラスが持つ全ての課題を求める
  function get_class_all_submissions($class_id)
  {
    $stmt = $this->pdo->prepare("SELECT submissions.id, submissions.name as submission_name, submissions.dead_line,
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
      die($this->pdo->error);
    }
    $stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    if (!$success) {
      die($this->pdo->error);
    }
    $all_submissions_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $all_submissions_info;
  }

  // 課題を編集する
  function edit_submission($form, $submission_id, $current_time){
    $stmt = $this->pdo->prepare("UPDATE submissions
                             SET name = :submission_name,
                                 subject_id = :subject_id,
                                 dead_line = :dead_line,
                                 teacher_id = :teacher_id,
                                 updated_at = :updated_at
                           WHERE id = :submission_id");
    if (!$stmt) {
      die($this->pdo->error);
    }
    $stmt->bindValue(':submission_name', $form['submission_name'], PDO::PARAM_STR);
    $stmt->bindValue(':subject_id', $form['subject_id'], PDO::PARAM_INT);
    $stmt->bindValue(':dead_line', $form['dead_line'], PDO::PARAM_STR);
    $stmt->bindValue(':teacher_id', $form['teacher_id'], PDO::PARAM_INT);
    $stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
    $stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    if (!$success) {
      die($this->pdo->error);
    }
  }

  // 課題を削除する
  function delete_submission($submission_id, $teacher_id, $current_time)
  {
    $stmt = $this->pdo->prepare("UPDATE submissions
                             SET is_deleted = 1,
                                 teacher_id = :teacher_id,
                                 updated_at = :updated_at
                           WHERE id = :submission_id");
    if (!$stmt) {
      die($this->pdo->error);
    }
    $stmt->bindValue(':teacher_id', $teacher_id, PDO::PARAM_INT);
    $stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
    $stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    print_r($teacher_id);
    if (!$success) {
      die($this->pdo->error);
    }
  }
}
