<?php
require('dbconnect.php');

// 今年度
$this_year = (new \DateTime('-3 month'))->format('Y');


// 今日の日付
$today = date('Y-m-d');

// 画像がないユーザー用のimagesレコード
$no_image_id = 69;

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
function login_check()
{
  if (!$_SESSION['auth']['login']) {
    header('Location: log_in.php');
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

  $password_reset_url = "{$url}/{$type}/{$password_reset_token}";

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
