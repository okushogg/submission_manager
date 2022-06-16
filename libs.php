<?php
// 画像がないユーザー用のimagesレコード
$no_image_id = 69;

//不正な文字列をチェック
function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES);
}

function get_classes($value, $grade){
  $i = 0;
  while(!empty($value[$i]['grade']=$grade)){
    $grade = $value[$i]['grade'];
    $class = $value[$i]['class'];
    return "$grade - $class";
    $i ++;
  }
}

//日本時間を求める
function jp_time(){
  date_default_timezone_set('Asia/Tokyo');
  $jp_time = date('Y-m-d H:i:s');
  return $jp_time;
}
?>