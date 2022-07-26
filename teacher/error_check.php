<?php
// Sign_upのエラーチェック
if(!empty($form['first_name'])){
  $form['first_name'] = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
  if ($form['first_name'] === '') {
    $error['first_name'] = 'blank';
  } elseif (!preg_match("/\A[一-龠ァ-ヶぁ-ん]+\z/", $form['first_name'])) {
    $error['first_name'] = 'invalid_letter';
  }
}

if(!empty($form['last_name'])){
$form['last_name'] = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
if ($form['last_name'] === '') {
  $error['last_name'] = 'blank';
} elseif (!preg_match("/\A[一-龠ァ-ヶぁ-ん]+\z/", $form['last_name'])) {
  $error['last_name'] = 'invalid_letter';
}
}

//メールアドレスが入力されているかチェック
if(!empty($form['email'])){
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


// 課題作成・編集のエラーチェック
// パスワードのチェック
if(!empty($form['password'])){
  $form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  if ($form['password'] === '') {
    $error['password'] = 'blank';
  } elseif (strlen($form['password']) < 4) {
    $error['password'] = 'length';
  } elseif (!preg_match("/\A[a-zA-Z0-9]+\z/", $form['password'])) {
    $error['password'] = 'invalid_letter';
  }
}

// 画像のチェック
if ($_FILES) {
  $image = $_FILES['image'];
  if ($image['name'] !== '' && $image['error'] === 0) {
    $type = mime_content_type($image['tmp_name']);
    if ($type !== 'image/png' && $type !== 'image/jpeg') {
      $error['image'] = 'type';
    }
  }
}

// 課題名の確認
$form['submission_name'] = filter_input(INPUT_POST, 'submission_name', FILTER_SANITIZE_STRING);
if ($form['submission_name'] === '') {
  $error['submission_name'] = 'blank';
}

// クラスの確認
$form['class_id'] = filter_input(INPUT_POST, 'class_id', FILTER_SANITIZE_NUMBER_INT);
$class_id = intval($form['class_id']);
if ($form['class_id'] === "0") {
  $error['class_id'] = 'blank';
}

// 教科の確認
$form['subject_id'] = filter_input(INPUT_POST, 'subject_id', FILTER_SANITIZE_NUMBER_INT);
$subject_id = intval($form['subject_id']);
if ($form['subject_id'] === "0") {
  $error['subject_id'] = 'blank';
}

// 提出期限の確認
if(!empty($form['dead_line'])){
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
$grade = filter_input(INPUT_POST, 'grade', FILTER_SANITIZE_NUMBER_INT);
if ($grade === '0') {
  $error['grade'] = 'blank';
}
$smarty->assign('grade', $grade);

// クラス入力チェック
$class = filter_input(INPUT_POST, 'class', FILTER_SANITIZE_STRING);
if ($class === '-') {
  $error['class'] = 'blank';
}
$smarty->assign('class', $class);

// 同じクラスがないかチェック
$stmt_check = $db->prepare("SELECT * FROM classes WHERE year = ? AND grade = ? AND class = ?");
$success_check = $stmt_check->execute(array($this_year, $grade, $class));
if (!$success_check) {
  die($db->error);
}
$same_class_check = $stmt_check->fetchALL(PDO::FETCH_ASSOC);
if (count($same_class_check) >= 1) {
  $error['class'] = 'same_class';
}
