<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');
require('../private/error_check.php');
require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

// header tittle
$title = "教員 クラス登録ページ";
$smarty->assign('title', $title);

$form = [];
$error = [];

// this_year
$smarty->assign('this_year', $this_year);

// セッション内の情報
$teacher_info = $_SESSION['auth'];
$smarty->assign('teacher_info', $teacher_info);

// ログイン情報がないとログインページへ移る
login_check($teacher_page);

// 教員のログインか確認
$teacher_id = is_teacher_login();

// 教員がログインしていた場合
$image_id = $_SESSION['auth']['teacher_image_id'];

// 画像の情報を取得
$pic_info = get_pic_info($db, $image_id);
$smarty->assign('pic_info', $pic_info);

$form= [
  'grade' => '',
  'class' => ''
];

$error= [];

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
  // エラーチェック
  list($error, $form) = error_check($db, $this_year, $today, $form, "teachers");

  // 入力に問題がなければ
  if (empty($error)) {
    $stmt = $db->prepare("insert into classes(year, grade, class) values(?, ?, ?)");
    if (!$stmt) {
      die($db->error);
    }
    $success = $stmt->execute(array($this_year, $from['grade'], $form['class']));
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
