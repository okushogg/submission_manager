<?php

function error_check($db, $this_year, $today, $form)
{
  $error = [];

  // Sign_upのエラーチェック
  // 氏名のチェック
  if (isset($form['first_name'])) {
    $form['first_name'] = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    if ($form['first_name'] === '') {
      $error['first_name'] = 'blank';
    } elseif (!preg_match("/\A[一-龠ァ-ヶぁ-ん]+\z/", $form['first_name'])) {
      $error['first_name'] = 'invalid_letter';
    }
  }

  if (isset($form['last_name'])) {
    $form['last_name'] = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    if ($form['last_name'] === '') {
      $error['last_name'] = 'blank';
    } elseif (!preg_match("/\A[一-龠ァ-ヶぁ-ん]+\z/", $form['last_name'])) {
      $error['last_name'] = 'invalid_letter';
    }
  }

  //メールアドレスのチェック
  if (isset($form['email'])) {
    $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    if ($form['email'] === '') {
      $error['email'] = 'blank';
    } elseif (!preg_match("/\A([a-zA-Z0-9_\.\-]+)\@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9]{2,4})\z/", $form['email'])) {
      $error['email'] = 'not_like_email';
    } else {
      // 同一のメールアドレスがないかチェック
      $stmt = $db->prepare('select count(*) from teachers where email=?');
      if (!$stmt) {
        die($db->error);
      }
      $stmt->bindParam(1, $form['email'], PDO::PARAM_STR);
      $success = $stmt->execute();
      if (!$success) {
        die($db->error);
      }
      $cnt_str = $stmt->fetch(PDO::FETCH_COLUMN);
      $cnt = intval($cnt_str);
      if ($cnt > 0) {
        $error['email'] = 'duplicate';
      }
    }
  }

  // パスワードのチェック
  if (isset($form['password'])) {
    $form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    if ($form['password'] === '') {
      $error['password'] = 'blank';
    } elseif (strlen($form['password']) < 4) {
      $error['password'] = 'length';
    } elseif (!preg_match("/\A[a-zA-Z0-9]+\z/", $form['password'])) {
      $error['password'] = 'invalid_letter';
    }
  }

  // 出席番号のチェック
  if (isset($form['student_num'])) {
    $form['student_num'] = filter_input(INPUT_POST, 'student_num', FILTER_SANITIZE_NUMBER_INT);
    if ($form['student_num'] == 0) {
      $error['student_num'] = 'blank';
    }
  }

  // 性別のチェック
  if (isset($form['sex'])) {
    $form['sex'] = filter_input(INPUT_POST, 'sex', FILTER_SANITIZE_NUMBER_INT);
    if ($form['sex'] === null) {
      $error['sex'] = 'blank';
    }
  }

  // 在籍状況のチェック
  if (isset($form['is_active'])) {
    $form['is_active'] = filter_input(INPUT_POST, 'is_active');
    if ($form['is_active'] === null) {
      $error['is_active'] = 'blank';
    }
  }


  // 画像のチェック
  if (isset($form['image'])) {
    $image = $_FILES['image'];
    // 画像のファイル種類の判別
    if ($image['name'] !== '' && $image['error'] === 0) {
      $type = mime_content_type($image['tmp_name']);
      if ($type !== 'image/png' && $type !== 'image/jpeg') {
        $error['image'] = 'type';
      }
    }
    // 画像のファイルサイズの判別
    $file_size = $_FILES['image']['size'];
    if ($file_size !== '' && $image['error'] === 0) {
      if ($file_size >= 500000) {
        $error['image'] = 'size';
      }
    }
  }

  // 課題作成・編集のエラーチェック
  // 課題名の確認
  if (isset($form['submission_name'])) {
    $form['submission_name'] = filter_input(INPUT_POST, 'submission_name', FILTER_SANITIZE_STRING);
    if ($form['submission_name'] === '') {
      $error['submission_name'] = 'blank';
    }
  }

  // クラスの確認
  if (isset($form['class_id'])) {
    $form['class_id'] = filter_input(INPUT_POST, 'class_id', FILTER_SANITIZE_NUMBER_INT);
    if ($form['class_id'] === "0") {
      $error['class_id'] = 'blank';
    }
  }

  // 教科の確認
  if (isset($form['subject_id'])) {
    $form['subject_id'] = filter_input(INPUT_POST, 'subject_id', FILTER_SANITIZE_NUMBER_INT);
    $subject_id = intval($form['subject_id']);
    if ($form['subject_id'] === "0") {
      $error['subject_id'] = 'blank';
    }
  }

  // 提出期限の確認
  if (isset($form['dead_line'])) {
    $form['dead_line'] = filter_input(INPUT_POST, 'dead_line', FILTER_SANITIZE_NUMBER_INT);
    $dead_line = $form['dead_line'];
    if ($form['dead_line'] === '') {
      $error['dead_line'] = 'blank';
    } elseif ($today > $dead_line) {
      $error['dead_line'] = 'not_future_date';
    }
  }

  // クラス登録のエラーチェエク
  // 学年入力チェック
  if (isset($form['grade'])) {
    $form['grade'] = filter_input(INPUT_POST, 'grade', FILTER_SANITIZE_NUMBER_INT);
    if ($form['grade'] === '0') {
      $error['grade'] = 'blank';
    }
  }

  // クラス入力チェック
  if (isset($form['class'])) {
    $form['class'] = filter_input(INPUT_POST, 'class', FILTER_SANITIZE_STRING);
    if ($form['class'] === '-') {
      $error['class'] = 'blank';
    }
  }

  // 同じクラスがないかチェック
  if (isset($form['grade']) && isset($form['class'])) {
    $stmt_check = $db->prepare("SELECT * FROM classes WHERE year = ? AND grade = ? AND class = ?");
    $success_check = $stmt_check->execute(array($this_year, $form['grade'], $form['class']));
    if (!$success_check) {
      die($db->error);
    }
    $same_class_check = $stmt_check->fetchALL(PDO::FETCH_ASSOC);
    if (count($same_class_check) >= 1) {
      $error['class'] = 'same_class';
    }
  }
  return array($error, $form);
}
