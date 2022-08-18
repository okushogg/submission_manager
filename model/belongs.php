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
    try {
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
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }

  // 選択されたクラス情報を取得する
  function get_chosen_year_class($student_id, $chosen_year)
  {
    try {
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
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }

  // 新規登録した生徒の所属クラスを登録
  function register_new_student_belongs($student_id, $class_id, $student_num)
  {
    try {
      $stmt_belongs = $this->pdo->prepare('INSERT INTO belongs(student_id, class_id, student_num)
                                                VALUES (:student_id, :class_id, :student_num)');
      $stmt_belongs->bindParam(':student_id', $student_id, PDO::PARAM_INT);
      $stmt_belongs->bindParam(':class_id', $class_id, PDO::PARAM_INT);
      $stmt_belongs->bindParam(':student_num', $student_num, PDO::PARAM_INT);
      $stmt_belongs->execute();
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }

  // class_idから学年、クラス、出席番号を取得
  function get_class_student_num($student_id, $class_id)
  {
    try {
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
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }

  // student_numを求める
  function get_student_num_from_class_id($class_id)
  {
    try {
      $stmt = $this->pdo->prepare("SELECT student_id, student_num
                                     FROM belongs
                                    WHERE class_id = :class_id");
      $stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
      $stmt->execute();
      $student_num_array = $stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
      return $student_num_array;
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }

  // 生徒を検索する
  function search_students($form)
  {
    try {
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
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }
}
