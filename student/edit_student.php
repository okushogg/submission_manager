<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

$smarty->assign('this_year', $this_year);

// 今日の日付
$today = date('Y-m-d');

// 現在の時刻
$current_time = bkk_time();

// ログイン情報がないとログインページへ移る
login_check();

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

// 生徒情報を取得
$stmt = $db->prepare("SELECT first_name, last_name, sex, email, image_id, is_active
                      FROM students
                      WHERE id=:student_id");
if (!$stmt) {
  die($db->error);
}
$stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$student_info = $stmt->fetch(PDO::FETCH_ASSOC);
$smarty->assign('student_info', $student_info);

// 現在の所属クラスを求める
$this_year_class_stmt = $db->prepare("SELECT belongs.id as belongs_id, belongs.class_id, belongs.student_num as student_num,
                                             classes.class as class, classes.grade as grade, classes.year as year
                                      FROM belongs
                                      LEFT JOIN classes
                                      ON belongs.class_id = classes.id
                                      WHERE belongs.student_id = :student_id
                                      ORDER BY classes.year DESC");
$this_year_class_stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
$this_year_class_stmt->execute();
$this_year_class = $this_year_class_stmt->fetch(PDO::FETCH_ASSOC);
$class_id = $this_year_class['class_id'];
$smarty->assign('this_year_class', $this_year_class);


//現在在籍する学年から選択可能なクラスを求める
if ($this_year > $this_year_class['year']) {
  $sql = "SELECT id, grade, class FROM classes WHERE year=:year AND grade > :this_year_class";
} else {
  $sql = "SELECT id, grade, class FROM classes WHERE year=:year AND grade = :this_year_class";
}

$stmt = $db->prepare($sql);
if (!$stmt) {
  die($db->error);
}
$stmt->bindParam(':year', $this_year, PDO::PARAM_STR);
$stmt->bindParam(':this_year_class', $this_year_class['grade'], PDO::PARAM_STR);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$selectable_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$smarty->assign('selectable_classes', $selectable_classes);


// 生徒情報を更新をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 姓名の確認
  $form['first_name'] = filter_input(INPUT_POST, 'first_name');
  if ($form['first_name'] === '') {
    $error['first_name'] = 'blank';
  }

  $form['last_name'] = filter_input(INPUT_POST, 'last_name');
  if ($form['last_name'] === '') {
    $error['last_name'] = 'blank';
  }

  //メールアドレスが入力されているかチェック
  $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  if ($form['email'] === '') {
    $error['email'] = 'blank';
  }

  if ($form['image']) {
    // 画像のチェック
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
  $form['sex'] = filter_input(INPUT_POST, 'sex');
  if ($form['sex'] === '') {
    $error['sex'] = 'blank';
  }

  // 在籍状況のチェック
  $form['is_active'] = filter_input(INPUT_POST, 'is_active');

  if (empty($error)) {
    // 画像のアップロード
    if ($form['image']) {

      if ($image['name'] !== '') {
        $filename = date('Ymdhis') . '_' . $image['name'];
        if (!move_uploaded_file($image['tmp_name'], '../student_pictures/' . $filename)) {
          die('ファイルのアップロードに失敗しました');
        }
        $_SESSION['form']['image'] = $filename;
      } else {
        $_SESSION['form']['image'] = '';
      }

      // 画像がある場合
      if ($form['image'] != '') {
        $stmt = $db->prepare('insert into images(path) VALUES(:path)');
        if (!$stmt) {
          die($db->error);
        }
        $stmt->bindValue(':path', $form['image'], PDO::PARAM_STR);
        $success = $stmt->execute();
        if (!$success) {
          die($db->error);
        }
        $get_image_id = $db->prepare("select id from images where path = '" . $form['image'] . "'");
        $get_image_id->execute();
        $image_id_str = $get_image_id->fetch(PDO::FETCH_COLUMN);
        $image_id = intval($image_id_str);
        unset($stmt);
      } else {
        // 画像を指定しない場合は以前の写真を使用
        $image_id = $student_info['image_id'];
      }
    }
    // 情報をテーブルに保存
    $stmt = $db->prepare("UPDATE students
                           SET first_name = :first_name,
                               last_name = :last_name,
                               sex = :sex,
                               email = :email,
                               is_active = :is_active,
                               updated_at = :updated_at
                        WHERE id = :student_id");
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindValue(':first_name', $form['first_name'], PDO::PARAM_STR);
    $stmt->bindValue(':last_name', $form['last_name'], PDO::PARAM_STR);
    $stmt->bindValue(':sex', $form['sex'], PDO::PARAM_INT);
    $stmt->bindValue(':email', $form['email'], PDO::PARAM_STR);
    $stmt->bindValue(':is_active', $form['is_active'], PDO::PARAM_INT);
    $stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
    $stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }

    // 所属クラスと出席番号の情報をbelongsテーブルに保存
    // 進学した後新しいクラスを登録する場合
    if ($this_year > $this_year_class['year']) {
      $stmt_belongs = $db->prepare("INSERT INTO belongs(student_id, class_id, student_num)
                                  VALUES ($student_id, :class_id, :student_num)");
      $stmt_belongs->bindValue(':class_id', $form['class_id'], PDO::PARAM_INT);
      $stmt_belongs->bindValue(':student_num', $form['student_num'], PDO::PARAM_INT);
      $success = $stmt_belongs->execute();
      if (!$success) {
        die($db->error);
      }
      // 現在所属のクラスを変更する場合
    } else {
      $stmt_belongs = $db->prepare("UPDATE belongs
                                     SET class_id = :class_id,
                                         student_num = :student_num,
                                         updated_at = :update_at
                                   WHERE id = :belongs_id");
      $stmt_belongs->bindValue(':class_id', $form['class_id'], PDO::PARAM_INT);
      $stmt_belongs->bindValue(':student_num', $form['student_num'], PDO::PARAM_INT);
      $stmt_belongs->bindValue(':update_at', $current_time, PDO::PARAM_STR);
      $stmt_belongs->bindValue(':belongs_id', $this_year_class['belongs_id'], PDO::PARAM_INT);
      $success_belongs = $stmt_belongs->execute();
      if (!$success_belongs) {
        die($db->error);
      }
    }

    // セッション内のフォーム内容を破棄してstudent/home.phpへ
    unset($_SESSION['form']);
    header('Location: home.php');
  }
  $smarty->assign('form', $form);
  $smarty->assign('error', $error);
}

$smarty->caching = 0;
$smarty->display('student/edit_student.tpl');
