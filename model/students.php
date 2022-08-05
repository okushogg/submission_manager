<?php

class student
{

  // loginチェック
  function student_login_check($db, $email){
    $stmt = $db->prepare('select * from students where email=:email limit 1');
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $login_student = $stmt->fetch(PDO::FETCH_ASSOC);
    return  $login_student;
  }

  // student_idから生徒情報を取得する
  function get_student_info($db, $student_id)
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