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
  if (isset($_SESSION['auth']['teacher_id'])) {
    return true;
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
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");
  // 環境変数にてパスワードリセットURLとメールアドレスを管理
  $url = getenv('PASSWORD_RESET_URL');
  $mail_address = getenv('MAIL_ADDRESS');

  $password_reset_url = "{$url}/{$type}/password_reset_form.php?password_reset_token={$password_reset_token}";

  $subject =  "{$type}用パスワードリセットURLをお送りします";

  $body = <<<EOD
  下記URLへアクセスし、パスワードの変更を完了してください。<br>
  <a href="{$password_reset_url}">{$password_reset_url}</a>
  EOD;

  $headers = "From : {$mail_address} \n";
  $headers .= "Content-Type : text/html";

  $is_sent = mb_send_mail($to, $subject, $body, $headers);
  return $is_sent;
}
