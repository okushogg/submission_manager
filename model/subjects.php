<?php

class subject
{
  function get_all_subjects($db)
  {
    $subjects_stmt = $db->prepare("SELECT id, id as subject_id, name FROM subjects");
    $subjects_stmt->execute();
    $all_subjects = $subjects_stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
    return $all_subjects;
  }
}
