<?php
require('dbconnect.php');

// ページの種類
$student_page = "student_page";
$teacher_page = "teacher_page";

// 今年度
$this_year = (new \DateTime('-3 month'))->format('Y');
// $this_year =2023;

// 今日の日付
$today = date('Y-m-d');

// 画像がないユーザー用のimagesレコード
$no_image_id = 69;

// 写真の情報を取得
function get_pic_info($db, $image_id)
{
  $stmt = $db->prepare("select path from images where id=:id");
  if (!$stmt) {
    die($db->error);
  }
  $stmt->bindParam(':id', $image_id, PDO::PARAM_INT);
  $success = $stmt->execute();

  $pic_info = $stmt->fetch(PDO::FETCH_ASSOC);
  return $pic_info;
}

// 生徒情報を取得
function get_student_info($db, $student_id)
{
  $student_stmt = $db->prepare("SELECT first_name, last_name, sex, email, image_id, is_active
                                  FROM students
                                 WHERE id=:student_id");
  $student_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
  $student_stmt->execute();
  $student_info = $student_stmt->fetch(PDO::FETCH_ASSOC);
  return $student_info;
}

// 編集ページで変更があったか確認
function edit_check($form, $student_info, $this_year_class){
  $edit_check = [];
  $edit_check['last_name'] = $form['last_name'] === $student_info['last_name'];
  $edit_check['first_name'] = $form['first_name'] === $student_info['first_name'];
  $edit_check['sex'] = $form['sex'] === $student_info['sex'];
  $edit_check['class_id'] = $form['class_id'] === $this_year_class['class_id'];
  $edit_check['student_num'] = $form['student_num'] === $this_year_class['student_num'];
  $edit_check['email'] = $form['email'] === $student_info['email'];
  if(isset($_SESSION['auth']['teacher_id'])){
    $edit_check['is_active'] = $form['is_active'] === $student_info['is_active'];
  }
  return !in_array(false, $edit_check, true);
}

// 登録されている年度を全て取得
function get_years($db)
{
  $years_stmt = $db->prepare("SELECT DISTINCT year
                              FROM classes
                              ORDER BY year DESC");
  $years_stmt->execute();
  $all_years = $years_stmt->fetchAll(PDO::FETCH_ASSOC);
  return $all_years;
}

// ログインチェック
function login_check($page)
{
  if (!$_SESSION['auth']['is_login'] && $page === "student_page") {
    header('Location: ../student/log_in.php');
    exit();
  }
}

// 教員のログインか確認
function is_teacher_login()
{
  if ($_SESSION['auth']['pass_teacher_check']) {
    return true;
  } elseif (isset($_SESSION['auth']['teacher_id'])) {
    return $_SESSION['auth']['teacher_id'];
  } else {
    header('Location: ../teacher/teacher_check.php');
    exit();
  }
}

//不正な文字列をチェック
function h($value)
{
  return htmlspecialchars($value, ENT_QUOTES);
}

// 性別の表示
function display_sex($sex)
{
  switch ($sex) {
    case 0;
      echo "男";
      break;
    case 1;
      echo "女";
      break;
  }
}

// teacher/home.phpでクラス一覧を表示する
function get_classes($classes_info)
{
  $classes_array = array();
  foreach ($classes_info as $a) {
    $classes_array[$a['grade']][$a['class']] = $a;
  }
  return $classes_array;
}

//バンコクの時間を求める
function bkk_time()
{
  date_default_timezone_set('asia/bangkok');
  $bkk_time = date('Y-m-d H:i:s');
  return $bkk_time;
}

// パスワードリセット用のメール
function send_mail($to, $password_reset_token, $type)
{
  $smarty = new Smarty_submission_manager();
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");
  // 環境変数にてパスワードリセットURLとメールアドレスを管理
  $url = getenv('PASSWORD_RESET_URL');
  $mail_address = getenv('MAIL_ADDRESS');

  $password_reset_url = "{$url}/{$type}/password_reset_form.php?password_reset_token={$password_reset_token}";

  $subject =  "{$type}用パスワードリセットURLをお送りします";
  $smarty->assign('password_reset_url', $password_reset_url);
  $body = $smarty->fetch('common/mail.tpl');

  $headers = "From : {$mail_address} \n";
  $headers .= "Content-Type : text/html";

  $is_sent = mb_send_mail($to, $subject, $body, $headers);
  return $is_sent;
}


// 画像ファイルリサイズ
function makeThumb($pic_dir)
{
  $image = $_FILES['image'];
  $original_file = $image['tmp_name'];
  $type = mime_content_type($image['tmp_name']);
  $filename = date('Ymdhis') . '_' . $image['name'];

  // getimagesize関数 オリジナル画像の横幅・高さを取得
  list($original_width, $original_height) = getimagesize($original_file);

  // サムネイルの横幅を指定
  $thumb_width = 200;

  // サムネイルの高さを算出 round関数で四捨五入
  $thumb_height = round($original_height * $thumb_width / $original_width);

  // オリジナルファイルの画像リソース
  if($type === 'image/png'){
    $original_image = imagecreatefrompng($original_file);
  } elseif($type === 'image/jpeg'){
    $original_image = imagecreatefromjpeg($original_file);
  } else {
    die('ファイルのアップロードに失敗しました');
  }


  // サムネイルの画像リソース
  $thumb_image = imagecreatetruecolor($thumb_width, $thumb_height);

  // サムネイル画像の作成
  imagecopyresized(
    $thumb_image,
    $original_image,
    0,
    0,
    0,
    0,
    $thumb_width,
    $thumb_height,
    $original_width,
    $original_height
  );

  // サムネイル画像の出力
  $save_path = "../$pic_dir/$filename";
  if($type === 'image/png'){
    imagepng($thumb_image, $save_path);
  } elseif($type === 'image/jpeg'){
    imagejpeg($thumb_image, $save_path);
  } else {
    die('ファイルのアップロードに失敗しました');
  }

  // 画像リソースを破棄
  imagedestroy($original_image);
  imagedestroy($thumb_image);

  return $filename;
}