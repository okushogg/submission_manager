<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

$form = [];
$error = [];

// this_year
$smarty->assign('this_year', $this_year);

// セッション内の情報
$teacher_info = $_SESSION['auth'];
$smarty->assign('teacher_info', $teacher_info);

// ログイン情報がないとログインページへ移る
login_check();

// 教員がログインしていた場合
$teacher_id = $_SESSION['auth']['teacher_id'];
$image_id = $_SESSION['auth']['teacher_image_id'];

// 画像の情報を取得
$stmt = $db->prepare("select path from images where id=:id");
if (!$stmt) {
  die($db->error);
}
$stmt->bindParam(':id', $image_id, PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$pic_info = $stmt->fetch(PDO::FETCH_ASSOC);
$smarty->assign('pic_info', $pic_info);

$grades = [
  "-" => 0,
  "1" => 1,
  "2" => 2,
  "3" => 3
];
$smarty->assign('grades', $grades);

$classes = [
  "-" => '-',
  "A" => 'A',
  "B" => 'B',
  "C" => 'C'
];
$smarty->assign('classes', $classes);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

  // 入力に問題がなければ
  if (empty($error)) {
    $stmt = $db->prepare("insert into classes(year, grade, class) values(?, ?, ?)");
    if (!$stmt) {
      die($db->error);
    }
    $success = $stmt->execute(array($this_year, $grade, $class));
    if (!$success) {
      die($db->error);
    }
    header('Location: home.php');
  }
  $smarty->assign('form',$form);
  $smarty->assign('error',$error);
}
$smarty->caching = 0;
$smarty->display('teacher/register_class.tpl');
