<?php

class image
{
  // image_idから画像のファイル名を取得
  function get_pic_info($db, $image_id)
  {
    $stmt = $db->prepare("SELECT path
                            FROM images
                           WHERE id=:id");
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(':id', $image_id, PDO::PARAM_INT);
    $success = $stmt->execute();

    $pic_info = $stmt->fetch(PDO::FETCH_ASSOC);
    return $pic_info;
  }

  // studentの登録時に画像を保存
  function pic_register($db, $file_name){
    $stmt = $db->prepare('INSERT INTO images(path)
                               VALUES (:path)');
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(':path', $file_name, PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $get_image_id = $db->prepare("SELECT id
                                    FROM images
                                   WHERE path = :path ");
    $get_image_id>bindParam(':path', $file_name, PDO::PARAM_STR);
    $get_image_id->execute();
    $registered_image_id = $get_image_id->fetch(PDO::FETCH_COLUMN);
    return $registered_image_id;
  }
}
