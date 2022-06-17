<?php
require('dbconnect.php');
// 画像がないユーザー用のimagesレコード
$no_image_id = 69;

//不正な文字列をチェック
function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES);
}

function get_classes($classes_info){
  $all_classes_array = array();
  foreach($classes_info as $class_info){
  $all_classes_array = array(
    $class_info['grade']=>
    arry(
       => $class_info['grade']
      'class' => $class_info['class']
    )
  );
  }
  return $all_classes_array;
}

//日本時間を求める
function jp_time(){
  date_default_timezone_set('Asia/Tokyo');
  $jp_time = date('Y-m-d H:i:s');
  return $jp_time;
}
?>