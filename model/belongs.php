<?php

class belong extends database
{
  // DB接続
  function __construct()
  {
    parent::connect_db();
  }

  // 所属していた全てのクラス情報を取得する
  function get_all_belonged_classes($student_id)
  {
    $classes_stmt = $this->pdo->prepare("SELECT classes.grade, belongs.id, belongs.class_id,
                                         classes.grade, classes.year,  classes.class, belongs.student_num
                                    FROM belongs
                               LEFT JOIN classes
                                      ON belongs.class_id = classes.id
                                   WHERE student_id=:student_id
                                ORDER BY classes.year ASC");
    $classes_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $classes_stmt->execute();
    $all_belonged_classes = $classes_stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
    return $all_belonged_classes;
  }

  // 選択されたクラス情報を取得する
  function get_chosen_year_class($student_id, $chosen_year)
  {
    $chosen_year_class_stmt = $this->pdo->prepare("SELECT belongs.id as belongs_id, belongs.class_id, belongs.student_num as student_num,
                                                   classes.class as class, classes.grade as grade, classes.year as year
                                              FROM belongs
                                         LEFT JOIN classes
                                                ON belongs.class_id = classes.id
                                             WHERE belongs.student_id = :student_id
                                               AND classes.year = :year");
    $chosen_year_class_stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
    $chosen_year_class_stmt->bindValue(':year', $chosen_year, PDO::PARAM_INT);
    $chosen_year_class_stmt->execute();
    $chosen_year_class = $chosen_year_class_stmt->fetch(PDO::FETCH_ASSOC);
    return $chosen_year_class;
  }

  // 新規登録した生徒の所属クラスを登録
  function register_new_student_belongs($student_id, $class_id, $student_num)
  {
    $stmt_belongs = $this->pdo->prepare('INSERT INTO belongs(student_id, class_id, student_num)
                                       VALUES (:student_id, :class_id, :student_num)');
    if (!$stmt_belongs) {
      die($this->pdo->error);
    }
    $stmt_belongs->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt_belongs->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $stmt_belongs->bindParam(':student_num', $student_num, PDO::PARAM_INT);
    $success_belongs = $stmt_belongs->execute();
    if (!$success_belongs) {
      die($this->pdo->error);
    }
  }

  // class_idから学年、クラス、出席番号を取得
  function get_class_student_num($student_id, $class_id)
  {
    $class_stmt = $this->pdo->prepare("SELECT belongs.id, belongs.class_id, belongs.student_num as student_num,
                                       classes.year as year, classes.class as class, classes.grade as grade
                                  FROM belongs
                             LEFT JOIN classes
                                    ON belongs.class_id = classes.id
                                 WHERE belongs.student_id = :student_id
                                   AND belongs.class_id = :class_id");
    $class_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $class_stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $class_stmt->execute();
    $chosen_class = $class_stmt->fetch(PDO::FETCH_ASSOC);
    return $chosen_class;
  }

  // 所属クラスと出席番号の情報をbelongsテーブルに保存
  function update_belonged_class_and_student_num($student_id, $this_year, $this_year_class, $form, $current_time)
  {
    // 進学した後新しいクラスを登録する場合
    if ($this_year > $this_year_class['year']) {
      $stmt_belongs = $this->pdo->prepare("INSERT INTO belongs(student_id, class_id, student_num)
                                         VALUES (:student_id, :class_id, :student_num)");
      $stmt_belongs->bindParam(':student_id', $student_id, PDO::PARAM_INT);
      $stmt_belongs->bindValue(':class_id', $form['class_id'], PDO::PARAM_INT);
      $stmt_belongs->bindValue(':student_num', $form['student_num'], PDO::PARAM_INT);
      $success = $stmt_belongs->execute();
      if (!$success) {
        die($this->pdo->error);
      }
      // 現在所属のクラスを変更する場合
    } else {
      $stmt_belongs = $this->pdo->prepare("UPDATE belongs
                                       SET class_id = :class_id,
                                           student_num = :student_num,
                                           updated_at = :update_at
                                     WHERE id = :belongs_id");
      $stmt_belongs->bindValue(':class_id', $form['class_id'], PDO::PARAM_INT);
      $stmt_belongs->bindValue(':student_num', $form['student_num'], PDO::PARAM_INT);
      $stmt_belongs->bindValue(':update_at', $current_time, PDO::PARAM_STR);
      $stmt_belongs->bindValue(':belongs_id', $this_year_class['belongs_id'], PDO::PARAM_INT);
      $success_belongs = $stmt_belongs->execute();
      if (!$success_belongs) {
        die($this->pdo->error);
      }
    }
  }

  // student_numを求める
  function get_student_num_from_class_id($class_id)
  {
    $belong_stmt = $this->pdo->prepare("SELECT student_id, student_num
                                   FROM belongs
                                  WHERE class_id = $class_id");
    $belong_stmt->execute();
    $student_num_array = $belong_stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
    return $student_num_array;
  }

  // 指定されたclass_idを持つ全てのstudent_idを求める(除籍済を除く)
  function get_students_belong_to_class($class_id)
  {
    $student_stmt = $this->pdo->prepare("SELECT b.student_id as student_id, b.student_num as student_num
                                    FROM belongs as b
                              INNER JOIN students as s
                                      ON b.student_id = s.id
                                   WHERE class_id = :class_id
                                     AND s.is_active = 1
                                ORDER BY b.student_num");
    $student_stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
    $student_success = $student_stmt->execute();
    if (!$student_success) {
      die($this->pdo->error);
    }
    $all_student_id = $student_stmt->fetchAll(PDO::FETCH_ASSOC);
    return $all_student_id;
  }

  // 生徒を検索する
  function search_students($form)
  {
    $sql = "SELECT students.id as student_id, students.first_name, students.last_name, students.sex,
                   belongs.class_id, classes.year, classes.grade,
                   classes.class, belongs.student_num, students.is_active
              FROM belongs
         LEFT JOIN students ON belongs.student_id = students.id
         LEFT JOIN classes ON belongs.class_id = classes.id";
    $sql .= " WHERE ";
    $sql .= 'classes.year = :year';

    // 学年が選択されている場合
    if ($form['grade'] != 0) {
      $sql .= " AND ";
      $sql .= 'classes.grade = "' . h($form['grade']) . '"';
    }

    //クラスが選択されている場合
    if ($form['class'] != '-') {
      $sql .= " AND ";
      $sql .= 'classes.class = "' . h($form['class']) . '"';
    }

    //在籍状況が選択されている場合
    if ($form['is_active'] != '') {
      $sql .= " AND ";
      $sql .= 'students.is_active = "' . h($form['is_active']) . '"';
    }

    //氏が記入されている場合
    if ($form['last_name'] != '') {
      $sql .= " AND ";
      $sql .= 'students.last_name LIKE "%' . h($form['last_name']) . '%"';
    }

    //名が記入されている場合
    if ($form['first_name'] != '') {
      $sql .= " AND ";
      $sql .= 'students.first_name LIKE "%' . h($form['first_name']) . '%"';
    }
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(':year', $form['year'], PDO::PARAM_INT);
    $stmt->execute();
    $student_search_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $student_search_result;
  }
}
