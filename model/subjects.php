<?php

class subject extends database
{
  // DB接続
  function __construct()
  {
    parent::connect_db();
  }

  function get_all_subjects()
  {
    try {
      $subjects_stmt = $this->pdo->prepare("SELECT id, id as subject_id, name
                                              FROM subjects");
      $subjects_stmt->execute();
      $all_subjects = $subjects_stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
      return $all_subjects;
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }
}
