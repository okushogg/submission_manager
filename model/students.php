<?php
class student
{
  public function get_student_info($db, $student_id)
{
  $student_stmt = $db->prepare("SELECT first_name, last_name, sex, email, image_id, is_active
                                  FROM students
                                 WHERE id=:student_id");
  $student_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
  $student_stmt->execute();
  $student_info = $student_stmt->fetch(PDO::FETCH_ASSOC);
  return $student_info;
}
}
?>