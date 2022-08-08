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
    $chosen_year_class_stmt = $db->prepare("SELECT belongs.id, belongs.class_id, belongs.student_num as student_num,
                                                 classes.class as class, classes.grade as grade
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
}
