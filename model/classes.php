<?php

class classRoom
{
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
}
