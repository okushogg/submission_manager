<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');
require('../private/error_check.php');
require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

// header tittle
$title = "教員 クラス登録確認ページ";
$smarty->assign('title', $title);

// 直接register_class_check.phpに飛ばないようにする
if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  header('Location: register_class.php');
  exit();
}

$smarty->assign('form', $form);

$smarty->assign('grade', $form['grade']);
$smarty->assign('class', $form['class']);

// this_year
$smarty->assign('this_year', $this_year);


// ログイン情報がないとログインページへ移る
login_check($teacher_page);

// 教員のログインか確認
$teacher_id = is_teacher_login();

// 登録を押す
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $db->prepare("insert into classes(year, grade, class) values(?, ?, ?)");
    if (!$stmt) {
      die($db->error);
    }
    $success = $stmt->execute(array($this_year, $form['grade'], $form['class']));
    if (!$success) {
      die($db->error);
    }
    unset($_SESSION['form']);
    header('Location: home.php');
}

$smarty->caching = 0;
$smarty->display('teacher/register_class_check.tpl');
