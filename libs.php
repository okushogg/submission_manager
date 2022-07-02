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
function send_mail($to, $password_reset_token)
{
  mb_language("Japanese");
  mb_internal_encoding("UTF-8");

  // URLはご自身の環境に合わせてください
  $url = "localhost::8888/submissions_manager/student/reset_password_form.php?token={$password_reset_token}";

  $subject =  'パスワードリセット用URLをお送りします';

  $body = <<<EOD
    下記URLへアクセスし、パスワードの変更を完了してください。
    {$url}
    EOD;

  // Fromはご自身の環境に合わせてください
  $headers = "From : hoge@hoge.com\n";
  // text/htmlを指定し、html形式で送ることも可能
  $headers .= "Content-Type : text/plain";

  $is_sent = mb_send_mail($to, $subject, $body, $headers);
  return $is_sent;
}
