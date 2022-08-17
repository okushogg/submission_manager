<?php

class student_submission extends database
{
  // DB接続
  function __construct()
  {
    parent::connect_db();
  }

  // 課題の作成(クラスに所属する生徒を求める、課題を作成する、課題を各生徒に与える)
  function create_student_submission($form)
  {
    try {
      // DB接続
      $db = $this->pdo;
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // トランザクション開始
      $db->beginTransaction();

      // 処理1:指定されたclass_idを持つ全てのstudent_idを求める(除籍済を除く)
      $stmt = $db->prepare("SELECT b.student_id as student_id, b.student_num as student_num
                                    FROM belongs as b
                              INNER JOIN students as s
                                      ON b.student_id = s.id
                                   WHERE class_id = :class_id
                                     AND s.is_active = 1
                                ORDER BY b.student_num");
      $stmt->bindValue(':class_id', $form['class_id'], PDO::PARAM_INT);
      $stmt->execute();
      $all_student_id = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // 処理2:課題を作成する
      $stmt = $db->prepare("INSERT INTO submissions(name,
                                                  class_id,
                                                  subject_id,
                                                  dead_line,
                                                  teacher_id)
                               VALUES (:name,
                                       :class_id,
                                       :subject_id,
                                       :dead_line,
                                       :teacher_id)");
      $stmt->bindValue(':name', $form['submission_name'], PDO::PARAM_STR);
      $stmt->bindValue(':class_id', $form['class_id'], PDO::PARAM_INT);
      $stmt->bindValue(':subject_id', $form['subject_id'], PDO::PARAM_INT);
      $stmt->bindValue(':dead_line', $form['dead_line'], PDO::PARAM_STR);
      $stmt->bindValue(':teacher_id', $form['teacher_id'], PDO::PARAM_INT);
      $stmt->execute();
      $submission_id = $db->lastInsertId();

      // 処理3:生徒の記録student_submissionsを作成
      foreach ($all_student_id as $student_id) {
      $stmt = $db->prepare("INSERT INTO student_submissions(student_id,
                                                                     submission_id)
                                          VALUES (:student_id,
                                                  :submission_id)");
      $stmt->bindValue(':student_id', $student_id['student_id'], PDO::PARAM_INT);
      $stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
      $stmt->execute();
      }
      // コミット
      $db->commit();
      return true;
    } catch (PDOException $e) {
      // ロールバック
      $db->rollBack();
      echo 'DBエラー:' . $e->getMessage();
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
  function update_submission_score($score_array, $h_id, $today, $current_time)
  {
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
