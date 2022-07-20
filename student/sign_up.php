<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

// フォームの中身を確認、内容がなければ初期化
if (isset($_GET['action']) && isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  $form = [
    'first_name' => '',
    'last_name' => '',
    'sex' => '',
    'class_id' => '',
    'student_num' => '',
    'email' => '',
    'image_id' => '',
    'password' => '',
    'is_active' => true
  ];
}
$smarty->assign('form', $form);

// エラーの初期化
$error = [];
$smarty->assign('error', $error);

// 本年度のクラスを求める
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
$smarty->assign('this_year_classes', $this_year_classes);

// フォームの内容をチェック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
  $image = $_FILES['image'];
  if ($image['name'] !== '' && $image['error'] === 0) {
    $type = mime_content_type($image['tmp_name']);
    if ($type !== 'image/png' && $type !== 'image/jpeg') {
      $error['image'] = 'type';
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
  var_dump($form['sex']);
  if (empty($error)) {
    $_SESSION['form'] = $form;

    // 画像のアップロード
    if ($image['name'] !== '') {
      $filename = date('Ymdhis') . '_' . $image['name'];
      if (!move_uploaded_file($image['tmp_name'], '../student_pictures/' . $filename)) {
        die('ファイルのアップロードに失敗しました');
      }
      $_SESSION['form']['image'] = $filename;
    } else {
      $_SESSION['form']['image'] = '';
    }

    header('Location: check.php');
    exit();
  }
  $smarty->assign('form', $form);
  $smarty->assign('error', $error);
}
$smarty->caching = 0;
$smarty->display('student/sign_up.tpl');
?>