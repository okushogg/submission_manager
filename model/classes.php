<?php

class classRoom extends database
{
  // DB接続
  function __construct()
  {
    parent::connect_db();
  }

  // 今年の全クラスを取得
  function get_this_year_classes($this_year)
  {
    try {
      $stmt = $this->pdo->prepare("SELECT id, grade, class
                                     FROM classes
                                    WHERE year=:year");
      $stmt->bindParam(':year', $this_year, PDO::PARAM_STR);
      $stmt->execute();
      $this_year_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $this_year_classes;
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }

  // class_idから選択されたクラス情報を取得
  function get_chosen_class($class_id)
  {
    try {
      $stmt = $this->pdo->prepare("SELECT id AS class_id, grade, class
                                     FROM classes
                                    WHERE id=:id");
      $stmt->bindParam(':id', $class_id, PDO::PARAM_INT);
      $stmt->execute();
      $chosen_class = $stmt->fetch(PDO::FETCH_ASSOC);
      return $chosen_class;
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }

  // 生徒情報編集ページで選択可能なクラスの情報を提示する
  function get_selectable_classes($this_year, $this_year_class)
  {
    try {
      if ($this_year > $this_year_class['year']) {
        $sql = "SELECT id, grade, class
                  FROM classes
                 WHERE year=:year
                   AND grade > :this_year_class";
      } else {
        $sql = "SELECT id, grade, class
                  FROM classes
                 WHERE year=:year
                   AND grade = :this_year_class";
      }
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':year', $this_year, PDO::PARAM_STR);
      $stmt->bindParam(':this_year_class', $this_year_class['grade'], PDO::PARAM_STR);
      $stmt->execute();
      $selectable_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $selectable_classes;
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }

  // クラスを新しく登録する
  function register_class($form, $this_year)
  {
    try {
      $stmt = $this->pdo->prepare("INSERT INTO classes(year, grade, class)
                                        VALUES (:year, :grade, :class)");
      $stmt->bindParam(':year', $this_year, PDO::PARAM_STR);
      $stmt->bindParam(':grade', $form['grade'], PDO::PARAM_STR);
      $stmt->bindParam(':class', $form['class'], PDO::PARAM_STR);
      $stmt->execute();
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }

  // 登録されている年度を全て取得
  function get_years()
  {
    try {
      $years_stmt = $this->pdo->prepare("SELECT DISTINCT year
                                                    FROM classes
                                                ORDER BY year DESC");
      $years_stmt->execute();
      $all_years = $years_stmt->fetchAll(PDO::FETCH_ASSOC);
      return $all_years;
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }
}
