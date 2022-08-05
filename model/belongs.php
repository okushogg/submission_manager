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
}
