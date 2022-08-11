<?php

class belong
{

  // 所属していた全てのクラス情報を取得する
  function get_all_belonged_classes($db, $student_id)
  {
    $classes_stmt = $db->prepare("SELECT classes.grade, belongs.id, belongs.class_id,
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
  function get_chosen_year_class($db, $student_id, $chosen_year)
  {
    $chosen_year_class_stmt = $db->prepare("SELECT belongs.id as belongs_id, belongs.class_id, belongs.student_num as student_num,
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
  function register_new_student_belongs($db, $student_id, $class_id, $student_num)
  {
    $stmt_belongs = $db->prepare('INSERT INTO belongs(student_id, class_id, student_num)
                                  VALUES (:student_id, :class_id, :student_num)');
    if (!$stmt_belongs) {
      die($db->error);
    }
    $stmt_belongs->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt_belongs->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $stmt_belongs->bindParam(':student_num', $student_num, PDO::PARAM_INT);
    $success_belongs = $stmt_belongs->execute();
    if (!$success_belongs) {
      die($db->error);
    }
  }

  // class_idから学年、クラス、出席番号を取得
  function get_class_student_num($db, $student_id, $class_id)
  {
    $class_stmt = $db->prepare("SELECT belongs.id, belongs.class_id, belongs.student_num as student_num,
                                       classes.year as year, classes.class as class, classes.grade as grade
                                  FROM belongs
                             LEFT JOIN classes
                                    ON belongs.class_id = classes.id
                                 WHERE belongs.student_id = $student_id
                                   AND belongs.class_id = $class_id");
    $class_stmt->execute();
    $chosen_class = $class_stmt->fetch(PDO::FETCH_ASSOC);
    return $chosen_class;
  }

  // 所属クラスと出席番号の情報をbelongsテーブルに保存
  function update_belonged_class_and_student_num($db, $student_id, $this_year, $this_year_class, $form, $current_time)
  {
    // 進学した後新しいクラスを登録する場合
    if ($this_year > $this_year_class['year']) {
      $stmt_belongs = $db->prepare("INSERT INTO belongs(student_id, class_id, student_num)
                                  VALUES ($student_id, :class_id, :student_num)");
      $stmt_belongs->bindValue(':class_id', $form['class_id'], PDO::PARAM_INT);
      $stmt_belongs->bindValue(':student_num', $form['student_num'], PDO::PARAM_INT);
      $success = $stmt_belongs->execute();
      if (!$success) {
        die($db->error);
      }
      // 現在所属のクラスを変更する場合
    } else {
      $stmt_belongs = $db->prepare("UPDATE belongs
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
        die($db->error);
      }
    }
  }

  // student_numを求める
  function get_student_num_from_class_id($db, $class_id)
  {
    $belong_stmt = $db->prepare("SELECT student_id, student_num
                             FROM belongs
                             WHERE class_id = $class_id");
    $belong_stmt->execute();
    $student_num_array = $belong_stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
    return $student_num_array;
  }

  // 指定されたclass_idを持つ全てのstudent_idを求める(除籍済を除く)
  function get_students_belong_to_class($db, $class_id)
  {
    $student_stmt = $db->prepare("SELECT b.student_id as student_id, b.student_num as student_num
                                  FROM belongs AS b
                                  INNER JOIN students AS s
                                  ON b.student_id = s.id
                                  WHERE class_id = :class_id AND s.is_active = 1
                                  ORDER BY b.student_num");
    $student_stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
    $student_success = $student_stmt->execute();
    if (!$student_success) {
      die($db->error);
    }
    $all_student_id = $student_stmt->fetchAll(PDO::FETCH_ASSOC);
    return $all_student_id;
  }
}
