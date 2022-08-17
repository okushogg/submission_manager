<?php

class image extends database
{
  // DB接続
  function __construct()
  {
    parent::connect_db();
  }

  // image_idから画像のファイル名を取得
  function get_pic_info($image_id)
  {
    try {
      $stmt = $this->pdo->prepare("SELECT path
                                     FROM images
                                    WHERE id=:id");
      $stmt->bindParam(':id', $image_id, PDO::PARAM_INT);
      $stmt->execute();
      $pic_info = $stmt->fetch(PDO::FETCH_ASSOC);
      return $pic_info;
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }

  // studentの登録時に画像を保存
  function pic_register($file_name)
  {
    try {
      $stmt = $this->pdo->prepare('INSERT INTO images(path)
                                        VALUES (:path)');
      $stmt->bindParam(':path', $file_name, PDO::PARAM_STR);
      $stmt->execute();
      $get_image_id = $this->pdo->prepare("SELECT id
                                             FROM images
                                            WHERE path = :path ");
      $get_image_id->bindParam(':path', $file_name, PDO::PARAM_STR);
      $get_image_id->execute();
      $registered_image_id = $get_image_id->fetch(PDO::FETCH_COLUMN);
      return $registered_image_id;
    } catch (Exception $e) {
      return display_message($e->getMessage());
    }
  }
}
