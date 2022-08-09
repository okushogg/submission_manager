<?php

class student
{
  // loginチェック
  function student_login($db, $email)
  {
    $stmt = $db->prepare('SELECT * FROM students WHERE email=:email LIMIT 1');
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

  // 生徒情報を新規登録する
  function register_new_student($db, $form, $password, $image_id)
  {
    $stmt = $db->prepare('INSERT INTO students(last_name, first_name, email, password, image_id, sex)
                          VALUES(:last_name, :first_name, :email, :password, :image_id, :sex)');
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(':last_name', $form['last_name'], PDO::PARAM_STR);
    $stmt->bindParam(':first_name', $form['first_name'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $form['email'], PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':image_id', $image_id, PDO::PARAM_INT);
    $stmt->bindParam(':sex', $form['sex'], PDO::PARAM_INT);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
  }

  // 生徒情報の更新
  function update_student_info($db, $form, $current_time, $student_id)
  {
    $stmt = $db->prepare("UPDATE students
                           SET first_name = :first_name,
                               last_name = :last_name,
                               sex = :sex,
                               email = :email,
                               is_active = :is_active,
                               updated_at = :updated_at
                        WHERE id = :student_id");
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindValue(':first_name', $form['first_name'], PDO::PARAM_STR);
    $stmt->bindValue(':last_name', $form['last_name'], PDO::PARAM_STR);
    $stmt->bindValue(':sex', $form['sex'], PDO::PARAM_INT);
    $stmt->bindValue(':email', $form['email'], PDO::PARAM_STR);
    $stmt->bindValue(':is_active', $form['is_active'], PDO::PARAM_INT);
    $stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
    $stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
  }

  // password_reset_tokenの設定
  function set_password_reset_token($db, $email, $current_time){
    // メールアドレスがstudentsテーブルにあるか確認
    $stmt = $db->prepare("SELECT id as student_id, email
                            FROM students
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

    // password_reset_tokenをstudentsテーブルへ保存
    $pw_reset_stmt = $db->prepare("UPDATE students
                                      SET password_reset_token = :password_reset_token,
                                          updated_at = :updated_at
                                    WHERE id = :student_id ");
    $pw_reset_stmt->bindValue(':password_reset_token', $password_reset_token, PDO::PARAM_STR);
    $pw_reset_stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
    $pw_reset_stmt->bindValue(':student_id', $account_holder['student_id'], PDO::PARAM_INT);
    $success_pw_reset = $pw_reset_stmt->execute();
    return true;
    }
  }

  // passwordをリセット
  function reset_password($db, $current_time, $password, $password_reset_token){
    // password_reset_tokenが一致しているstudentのpasswordを変更
    $stmt = $db->prepare("UPDATE students
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
    header("Location: log_in.php");
    exit();
  }

}