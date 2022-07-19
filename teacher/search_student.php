<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

// セッション内の情報
$teacher_info = $_SESSION['auth'];
$smarty->assign('teacher_info', $teacher_info);

$form = [
  "last_name" => '',
  "first_name" => '',
  "year" => '',
  "grade" => '',
  "class" => '',
  "is_active" => ''
];
$smarty->assign('form', $form);

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

// 登録されている年度を全て取得
$all_years = get_years($db);
$smarty->assign('all_years', $all_years);

// 検索ボタン押下時
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $form['year'] = filter_input(INPUT_POST, 'year');
  $form['grade'] = filter_input(INPUT_POST, 'grade');
  $form['class'] = filter_input(INPUT_POST, 'class');
  $form['is_active'] = filter_input(INPUT_POST, 'is_active');
  $form['last_name'] = filter_input(INPUT_POST, 'last_name');
  $form['first_name'] = filter_input(INPUT_POST, 'first_name');
  //  var_dump($db_user);
  $sql = "SELECT students.id as student_id, students.first_name, students.last_name, students.sex,
                   belongs.class_id, classes.year, classes.grade,
                   classes.class, belongs.student_num, students.is_active
              FROM belongs
         LEFT JOIN students ON belongs.student_id = students.id
         LEFT JOIN classes ON belongs.class_id = classes.id";
  $sql .= " WHERE ";
  $sql .= 'classes.year = "' . $form['year'] . '"';

  // 学年が選択されている場合
  if ($form['grade'] != 0) {
    $sql .= " AND ";
    $sql .= 'classes.grade = "' . $form['grade'] . '"';
  }

  //クラスが選択されている場合
  if ($form['class'] != '-') {
    $sql .= " AND ";
    $sql .= 'classes.class = "' . $form['class'] . '"';
  }

  //在籍状況が選択されている場合
  if ($form['is_active'] != '') {
    $sql .= " AND ";
    $sql .= 'students.is_active = "' . $form['is_active'] . '"';
  }

  //氏が記入されている場合
  if ($form['last_name'] != '') {
    $sql .= " AND ";
    $sql .= 'students.last_name LIKE "%' . $form['last_name'] . '%"';
  }

  //名が記入されている場合
  if ($form['first_name'] != '') {
    $sql .= " AND ";
    $sql .= 'students.first_name LIKE "%' . $form['first_name'] . '%"';
  }

  $stmt = $db->query($sql);
  $student_search_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $smarty->assign('student_search_result', $student_search_result);
  $smarty->assign('form', $form);
}
$smarty->caching = 0;
$smarty->display('teacher/search_student.tpl');
