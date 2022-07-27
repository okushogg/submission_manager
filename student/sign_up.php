<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');
require('../private/error_check.php');
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
    'image' => '',
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
  // エラーチェック
  list($error, $form) = error_check($db, $this_year, $today, $form);


  // エラーがなければ画像を保存して、checkへ
  if (empty($error)) {
    $_SESSION['form'] = $form;

    // 画像のアップロード
    $image = $_FILES['image'];
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
  var_dump($error,$form,$_FILES['image']['size']);
  $smarty->assign('form', $form);
  $smarty->assign('error', $error);
}
$smarty->caching = 0;
$smarty->display('student/sign_up.tpl');
?>