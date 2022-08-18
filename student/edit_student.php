<?php
session_start();
require('../private/libs.php');
require('../private/error_check.php');

require_once('../private/set_up.php');
require_once('../model/classes.php');
require_once('../model/belongs.php');
require_once('../model/students.php');

$smarty = new Smarty_submission_manager();
$class = new classRoom();
$belong = new belong();
$student = new student();

// header tittle
$title = "生徒情報編集ページ";
$smarty->assign('title', $title);

$smarty->assign('this_year', $this_year);

// 今日の日付
$today = date('Y-m-d');

// 現在の時刻
$current_time = bkk_time();

// ログイン情報がないとログインページへ移る
login_check($student_page);

// 生徒がログインしていた場合
$student_id = $_SESSION['auth']['student_id'];

// フォームの初期化
$form = [
  'first_name' => '',
  'last_name' => '',
  'sex' => '',
  'class_id' => '',
  'student_num' => '',
  'email' => '',
  'image' => '',
  'is_active' => 1
];
$smarty->assign('form', $form);

$edit_check = [];
$error = [];
$smarty->assign('error', $error);

// 生徒情報を取得
$student_info = $student->get_student_info($student_id);
$smarty->assign('student_info', $student_info);

// 現在の所属クラスを求める
$this_year_class = $belong->get_chosen_year_class($student_id, $this_year);
$class_id = $this_year_class['class_id'];
$smarty->assign('this_year_class', $this_year_class);


//現在在籍する学年から選択可能なクラスを求める
$selectable_classes = $class->get_selectable_classes($this_year, $this_year_class);
$smarty->assign('selectable_classes', $selectable_classes);


// 生徒情報を更新をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // エラーチェック
  list($error, $form) = error_check($this_year, $today, $form, "students");

  // 編集があったか確認
  $edit_check = edit_check($form, $student_info, $this_year_class);
  if ($edit_check) {
    $error['edit_check'] = "no_edit";
  }

  // エラーがなかった場合
  if (empty($error)) {

    // 画像のデータがある場合
    if ($_FILES) {
      // 画像のアップロード
      $image = $_FILES['image'];
      if ($image['name'] !== '') {
        $file_name = date('Ymdhis') . '_' . $image['name'];
        $pic_dir = "student_pictures";
        if (!makeThumb($pic_dir)) {
          die('ファイルのアップロードに失敗しました');
        }
        $form['image'] = $file_name;
      } else {
        $form['image'] = '';
        $file_name = "";
      }
    }

    // 情報をテーブルに保存
    $result = $student->update_student_info($form, $student_info, $current_time, $this_year, $this_year_class, $file_name);
    if ($result) {
      header('Location: ../student/home.php');
      exit();
    }
  }
  $smarty->assign('form', $form);
  $smarty->assign('error', $error);
}

$smarty->caching = 0;
$smarty->display('student/edit_student.tpl');
