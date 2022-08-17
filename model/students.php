<?php

class student extends database
{
  // DB接続
  function __construct()
  {
    parent::connect_db();
  }

  // loginチェック
  function student_login($email)
  {
    $stmt = $this->pdo->prepare('SELECT *
                            FROM students
                           WHERE email=:email
                             AND is_active = 1
                           LIMIT 1');
    if (!$stmt) {
      die($this->pdo->error);
    }
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      die($this->pdo->error);
    }
    $login_student = $stmt->fetch(PDO::FETCH_ASSOC);
    return  $login_student;
  }

  // student_idから生徒情報を取得する
  function get_student_info($student_id)
  {
    $student_stmt = $this->pdo->prepare("SELECT id as student_id, first_name, last_name, sex, email, image_id, is_active
                                    FROM students
                                   WHERE id = :student_id");
    $student_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $student_stmt->execute();
    $student_info = $student_stmt->fetch(PDO::FETCH_ASSOC);
    return $student_info;
  }

  // 生徒情報を新規登録する
  function register_new_student($form, $password, $image_id)
  {
    $stmt = $this->pdo->prepare('INSERT INTO students(last_name, first_name, email, password, image_id, sex)
                               VALUES (:last_name, :first_name, :email, :password, :image_id, :sex)');
    if (!$stmt) {
      die($this->pdo->error);
    }
    $stmt->bindParam(':last_name', $form['last_name'], PDO::PARAM_STR);
    $stmt->bindParam(':first_name', $form['first_name'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $form['email'], PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':image_id', $image_id, PDO::PARAM_INT);
    $stmt->bindParam(':sex', $form['sex'], PDO::PARAM_INT);
    $success = $stmt->execute();
    if (!$success) {
      die($this->pdo->error);
    }
  }

  // 生徒情報の更新
  function update_student_info($form, $student_info, $current_time, $this_year, $this_year_class, $file_name)
  {
    try {
      // DB接続
      $db = $this->pdo;
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // トランザクション開始
      $db->beginTransaction();

      // 処理1:画像を保存、imageテーブルにレコードを作成、image_idを取得
      // 画像のデータがある場合
        // 画像のデータがある場合
        if ($form['image'] !== '') {
          $stmt = $db->prepare('INSERT INTO images(path)
                               VALUES (:path)');
          $stmt->bindParam(':path', $file_name, PDO::PARAM_STR);
          $stmt->execute();
          $get_image_id = $db->prepare("SELECT id
                                    FROM images
                                   WHERE path = :path ");
          $get_image_id->bindParam(':path', $file_name, PDO::PARAM_STR);
          $get_image_id->execute();
          $new_image_id = $get_image_id->fetch(PDO::FETCH_COLUMN);
        } else {
          // 画像を指定しない場合は以前の写真を使用
          $new_image_id = $student_info['image_id'];
          print_r('fail');
        }

      // 処理2:生徒情報を更新
      $stmt = $db->prepare("UPDATE students
                             SET first_name = :first_name,
                                 last_name = :last_name,
                                 sex = :sex,
                                 email = :email,
                                 image_id = :image_id,
                                 is_active = :is_active,
                                 updated_at = :updated_at
                           WHERE id = :student_id");
      $stmt->bindValue(':first_name', $form['first_name'], PDO::PARAM_STR);
      $stmt->bindValue(':last_name', $form['last_name'], PDO::PARAM_STR);
      $stmt->bindValue(':sex', $form['sex'], PDO::PARAM_INT);
      $stmt->bindValue(':email', $form['email'], PDO::PARAM_STR);
      $stmt->bindValue(':image_id', $new_image_id, PDO::PARAM_INT);
      $stmt->bindValue(':is_active', $form['is_active'], PDO::PARAM_INT);
      $stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
      $stmt->bindValue(':student_id', $student_info['student_id'], PDO::PARAM_INT);
      $stmt->execute();

      // 処理3:所属クラスと出席番号の情報をbelongsテーブルに保存
      // 進学した後新しいクラスを登録する場合
      if ($this_year > $this_year_class['year']) {
        $stmt = $db->prepare("INSERT INTO belongs(student_id, class_id, student_num)
                                         VALUES (:student_id, :class_id, :student_num)");
        $stmt->bindParam(':student_id', $student_info['student_id'], PDO::PARAM_INT);
        $stmt->bindValue(':class_id', $form['class_id'], PDO::PARAM_INT);
        $stmt->bindValue(':student_num', $form['student_num'], PDO::PARAM_INT);
        $stmt->execute();
      } else {
      // 現在所属のクラスを変更する場合
        $stmt = $db->prepare("UPDATE belongs
                                       SET class_id = :class_id,
                                           student_num = :student_num,
                                           updated_at = :update_at
                                     WHERE id = :belongs_id");
        $stmt->bindValue(':class_id', $form['class_id'], PDO::PARAM_INT);
        $stmt->bindValue(':student_num', $form['student_num'], PDO::PARAM_INT);
        $stmt->bindValue(':update_at', $current_time, PDO::PARAM_STR);
        $stmt->bindValue(':belongs_id', $this_year_class['belongs_id'], PDO::PARAM_INT);
        $stmt->execute();
      }

      // コミット
      $db->commit();
      return true;
    } catch (PDOException $e) {
      // ロールバック
      $db->rollBack();
      echo 'DBエラー:' . $e->getMessage();
    }
  }

  // password_reset_tokenの設定
  // password_reset_tokenを登録する
  function set_password_reset_token($email, $current_time)
  {
    // メールアドレスがstudentsテーブルにあるか確認
    $stmt = $this->pdo->prepare("SELECT id as student_id, email
                            FROM students
                           WHERE email = :email
                             AND is_active = 1");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      $this->pdo->error;
    }
    $account_holder = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($account_holder) {
      // password reset token生成
      $password_reset_token = bin2hex(random_bytes(18));

      // メールが送信されたらpassword_reset_tokenをstudentsテーブルへ保存
      $pw_reset_stmt = $this->pdo->prepare("UPDATE students
                                        SET password_reset_token = :password_reset_token,
                                            updated_at = :updated_at
                                      WHERE id = :student_id ");
      $pw_reset_stmt->bindValue(':password_reset_token', $password_reset_token, PDO::PARAM_STR);
      $pw_reset_stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
      $pw_reset_stmt->bindValue(':student_id', $account_holder['student_id'], PDO::PARAM_INT);
      $success_pw_reset = $pw_reset_stmt->execute();
      if ($success_pw_reset) {
        // DBへtokenが保存されたらメールを送信
        send_mail($account_holder['email'], $password_reset_token, "student");
      } else {
        $this->pdo->error;
      }
    }
  }

  // passwordをリセット
  function reset_password($current_time, $password, $password_reset_token)
  {
    // password_reset_tokenが一致しているstudentのpasswordを変更
    $stmt = $this->pdo->prepare("UPDATE students
                             SET password = :password,
                                 updated_at = :updated_at,
                                 password_reset_token = null
                           WHERE password_reset_token = :password_reset_token");
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);
    $stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
    $stmt->bindValue(':password_reset_token', $password_reset_token, PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      $this->pdo->error;
    }
    header("Location: log_in.php");
    exit();
  }
}
