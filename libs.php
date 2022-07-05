<?php
require('dbconnect.php');

// 今年度
$this_year = (new \DateTime('-3 month'))->format('Y');

// 今日の日付
$today = date('Y-m-d');

// 画像がないユーザー用のimagesレコード
$no_image_id = 69;

//不正な文字列をチェック
function h($value)
{
  return htmlspecialchars($value, ENT_QUOTES);
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

  $url = "http://localhost::8888/submissions_manager/{$type}/reset_password_form.php?password_reset_token={$password_reset_token}";

  $subject =  "{$type}用パスワードリセットURLをお送りします";

  $body = <<<EOD
  下記URLへアクセスし、パスワードの変更を完了してください。<br>
  <a href="{$url}">{$url}</a>
  EOD;

  $headers = "From : hoge@hoge.com\n";
  $headers .= "Content-Type : text/html";

  $is_sent = mb_send_mail($to, $subject, $body, $headers);
  return $is_sent;
}
