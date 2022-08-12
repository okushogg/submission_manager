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

  // password_reset_tokenを登録する
  function set_password_reset_token($db, $email, $current_time)
  {
    // メールアドレスがteachersテーブルにあるか確認
    $stmt = $db->prepare("SELECT id as teacher_id, email
                          FROM teachers
                          WHERE email = :email
                          AND is_active = 1");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      $db->error;
    }
    $account_holder = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($account_holder) {
      // password reset token生成
      $password_reset_token = bin2hex(random_bytes(18));

      // メールが送信されたらpassword_reset_tokenをteachersテーブルへ保存
      $pw_reset_stmt = $db->prepare("UPDATE teachers
                SET password_reset_token = :password_reset_token,
                    updated_at = :updated_at
              WHERE id = :teacher_id ");
      $pw_reset_stmt->bindValue(':password_reset_token', $password_reset_token, PDO::PARAM_STR);
      $pw_reset_stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
      $pw_reset_stmt->bindValue(':teacher_id', $account_holder['teacher_id'], PDO::PARAM_INT);
      $success_pw_reset = $pw_reset_stmt->execute();
      if ($success_pw_reset) {
        // DBへtokenが保存されたらメールを送信
        send_mail($account_holder['email'], $password_reset_token, "teacher");
      } else {
        $db->error;
      }
    }
  }

  // passwordをリセット
  function reset_password($db, $current_time, $password, $password_reset_token)
  {
    // password_reset_tokenが一致しているteacherのpasswordを変更
    $stmt = $db->prepare("UPDATE teachers
                           SET password = :password,
                               updated_at = :updated_at,
                               password_reset_token = null
                         WHERE password_reset_token = :password_reset_token");
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);
    $stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
    $stmt->bindValue(':password_reset_token', $password_reset_token, PDO::PARAM_STR);

    $success = $stmt->execute();
    if (!$success) {
      $db->error;
    }
  }
}
