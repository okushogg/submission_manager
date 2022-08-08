<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');
require('../private/error_check.php');

require_once('../private/set_up.php');
require_once('../model/students.php');
require_once('../model/classes.php');
require_once('../model/images.php');
require_once('../model/belongs.php');


$smarty = new Smarty_submission_manager();
$class = new classRoom();
$image = new image();
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

$error = [];
$smarty->assign('error', $error);

// 生徒情報を取得
$student_info = get_student_info($db, $student_id);
$smarty->assign('student_info', $student_info);

// 現在の所属クラスを求める

$this_year_class = $belong->get_chosen_year_class($db, $student_id, $this_year);
$class_id = $this_year_class['class_id'];
$smarty->assign('this_year_class', $this_year_class);


//現在在籍する学年から選択可能なクラスを求める
$selectable_classes = $class->get_selectable_classes($db, $this_year, $this_year_class);
$smarty->assign('selectable_classes', $selectable_classes);


// 生徒情報を更新をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // エラーチェック
  list($error, $form) = error_check($db, $this_year, $today, $form, "students");

  // エラーがなかった場合
  if (empty($error)) {

    // 画像のデータがある場合
    if ($_FILES) {

      // 画像のアップロード
      $image = $_FILES['image'];
      if ($image['name'] !== '') {
        $filename = date('Ymdhis') . '_' . $image['name'];
        $pic_dir = "teacher_pictures";
        if (!makeThumb($pic_dir)) {
          die('ファイルのアップロードに失敗しました');
        }
        $_SESSION['form']['image'] = $filename;
      } else {
        $_SESSION['form']['image'] = '';
      }

      // 画像がある場合
      if ($form['image'] != '') {
        $image_id = $image->student_pic_register($db, $filename);
        unset($stmt);
      } else {
        // 画像を指定しない場合は以前の写真を使用
        $image_id = $student_info['image_id'];
      }
    }
    // 情報をテーブルに保存
    $student->update_student_info($db, $form, $current_time, $student_id);

    // 所属クラスと出席番号の情報をbelongsテーブルに保存
    $belong->update_belonged_class_and_student_num($db,$student_id, $this_year, $this_year_class, $form, $current_time);

    header('Location: ../student/home.php');
  }
  $smarty->assign('form', $form);
  $smarty->assign('error', $error);
}

$smarty->caching = 0;
$smarty->display('student/edit_student.tpl');
