<?php

class image
{
  // image_idから画像のファイル名を取得
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
}
