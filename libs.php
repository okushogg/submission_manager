<?php
require('dbconnect.php');

// 画像がないユーザー用のimagesレコード
$no_image_id = 69;

//不正な文字列をチェック
function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES);
}

// student/home.phpでclass_idから学年、クラスの情報を取得する


// teacher/home.phpでクラス一覧を表示する
function get_classes($classes_info){
  $classes_array = array();
  foreach($classes_info as $a){
  $classes_array[$a['grade']][$a['class']]=$a;
  }
  return $classes_array;
}

//バンコクの時間を求める
function jp_time(){
  date_default_timezone_set('asia/bangkok');
  $bkk_time = date('Y-m-d H:i:s');
  return $bkk_time;
}
?>