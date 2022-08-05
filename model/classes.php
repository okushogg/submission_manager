<?php

class classRoom
{
  // 今年の全クラスを取得
  function get_this_year_classes($db, $this_year)
  {
    $stmt = $db->prepare("SELECT id, grade, class FROM classes WHERE year=:year");
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(':year', $this_year, PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $this_year_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $this_year_classes;
  }

  // class_idから選択されたクラス情報を取得
  function get_chosen_class($db, $class_id)
  {
    $stmt = $db->prepare("select grade, class from classes where id=:id");
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(':id', $class_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $chosen_class = $stmt->fetch(PDO::FETCH_ASSOC);
    return $chosen_class;
  }
}
