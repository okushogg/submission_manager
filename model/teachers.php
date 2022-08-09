<?php

class teacher
{
  // loginチェック
  function teacher_login($db, $email)
  {
    $stmt = $db->prepare('SELECT * FROM teachers WHERE email=:email LIMIT 1');
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $login_teacher = $stmt->fetch(PDO::FETCH_ASSOC);
    return  $login_teacher;
  }

  // 教員情報を新規登録する
  function register_new_teacher($db, $form, $password, $image_id)
  {
  $stmt = $db->prepare('INSERT INTO teachers(last_name, first_name, email, password, image_id)
                        VALUES(:last_name, :first_name, :email, :password, :image_id)');
  if (!$stmt) {
    die($db->error);
  }
  $stmt->bindParam(':last_name', $form['last_name'], PDO::PARAM_STR);
  $stmt->bindParam(':first_name', $form['first_name'], PDO::PARAM_STR);
  $stmt->bindParam(':email', $form['email'], PDO::PARAM_STR);
  $stmt->bindParam(':password', $password, PDO::PARAM_STR);
  $stmt->bindParam(':image_id', $image_id, PDO::PARAM_INT);
  $success = $stmt->execute();
  if (!$success) {
    die($db->error);
  }
  }
}
