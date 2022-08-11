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

  // 生徒情報編集ページで選択可能なクラスの情報を提示する
  function get_selectable_classes($db, $this_year, $this_year_class)
  {
    if ($this_year > $this_year_class['year']) {
      $sql = "SELECT id, grade, class FROM classes WHERE year=:year AND grade > :this_year_class";
    } else {
      $sql = "SELECT id, grade, class FROM classes WHERE year=:year AND grade = :this_year_class";
    }
    $stmt = $db->prepare($sql);
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(':year', $this_year, PDO::PARAM_STR);
    $stmt->bindParam(':this_year_class', $this_year_class['grade'], PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $selectable_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $selectable_classes;
  }

  // クラスを新しく登録する
  function register_class($db, $form, $this_year)
  {
    $stmt = $db->prepare("INSERT INTO classes(year, grade, class)
                          VALUES(:year, :grade, :class)");
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(':year', $this_year, PDO::PARAM_STR);
    $stmt->bindParam(':grade', $form['grade'], PDO::PARAM_STR);
    $stmt->bindParam(':class', $form['class'], PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
  }
}
