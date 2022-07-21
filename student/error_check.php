<?php

// 姓名の確認
$form['first_name'] = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
if ($form['first_name'] === '') {
  $error['first_name'] = 'blank';
}

$form['last_name'] = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
if ($form['last_name'] === '') {
  $error['last_name'] = 'blank';
}

//メールアドレスが入力されているかチェック
$form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
if ($form['email'] === '') {
  $error['email'] = 'blank';
} else {
  // 同一のメールアドレスがないかチェック
  $stmt = $db->prepare('select count(*) from students where email=?');
  if (!$stmt) {
    die($db->error);
  }
  $stmt->bindParam(1, $form['email'], PDO::PARAM_STR);
  // $success = $stmt->execute(array($form['email']));
  $success = $stmt->execute();
  if (!$success) {
    die($db->error);
  }
  $cnt_string = $stmt->fetch(PDO::FETCH_COLUMN);
  $cnt = intval($cnt_string);
  // var_dump($cnt);

  if ($cnt > 0) {
    $error['email'] = 'duplicate';
  }
}


// パスワードのチェック
$form['password'] = filter_input(INPUT_POST, 'password');
if ($form['password'] === '') {
  $error['password'] = 'blank';
} elseif (strlen($form['password']) < 4) {
  $error['password'] = 'length';
}

// 画像のチェック
if ($_FILES){
  $image = $_FILES['image'];
  if ($image['name'] !== '' && $image['error'] === 0) {
    $type = mime_content_type($image['tmp_name']);
    if ($type !== 'image/png' && $type !== 'image/jpeg') {
      $error['image'] = 'type';
    }
  }
}

// 学年とクラスのチェック
$form['class_id'] = filter_input(INPUT_POST, 'class_id', FILTER_SANITIZE_NUMBER_INT);
if ($form['class_id'] == 0) {
  $error['class_id'] = 'blank';
}

// 出席番号のチェック
$form['student_num'] = filter_input(INPUT_POST, 'student_num', FILTER_SANITIZE_NUMBER_INT);
if ($form['student_num'] == 0) {
  $error['student_num'] = 'blank';
}


// 性別のチェック
$form['sex'] = filter_input(INPUT_POST, 'sex', FILTER_SANITIZE_NUMBER_INT);
if ($form['sex'] === null) {
  $error['sex'] = 'blank';
}

// 在籍状況のチェック
$form['is_active'] = filter_input(INPUT_POST, 'is_active');
if($form['is_active'] === null) {
  $error['is_active'] = 'blank';
}

?>