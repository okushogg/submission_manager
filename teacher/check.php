<?php
session_start();
require('../libs.php');
require('../dbconnect.php');

// 直接check.phpに飛ばないようにする
if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  header('Location: index.php');
  exit();
}

$form = $_SESSION['form'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 画像がある場合
  if ($form['image'] !== '') {
    $stmt = $db->prepare('insert into images(path) VALUES(?)');
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(1, $form['image'], PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $get_image_id = $db->prepare("select id from images where path = '". $form['image']."'");
    $get_image_id->execute();
    $image_id_str = $get_image_id->fetch(PDO::FETCH_COLUMN);
    $image_id = intval($image_id_str);
    var_dump($image_id);
    var_dump($form['image']);
    unset($stmt);
  } else {
    // 画像がない場合
    $image_id = $no_image_id;
  }

  // パスワードをDBに直接保管しない
  $password = password_hash($form['password'], PASSWORD_DEFAULT);
  $stmt = $db->prepare('insert into teachers(last_name, first_name, email, password, image_id) VALUES(?, ?, ?, ?, ?)');
  $success = $stmt->execute(array($form['last_name'], $form['first_name'], $form['email'], $password, $image_id));
    // （これも使える）
    // $stmt = $db->prepare('insert into teachers(last_name, first_name, email, password, image_id) VALUES(:last_name, :first_name, :email, :password, :image_id)');
    // $stmt->bindParam(':last_name', $form['last_name'], PDO::PARAM_STR);
    // $stmt->bindParam(':first_name', $form['first_name'], PDO::PARAM_STR);
    // $stmt->bindParam(':email', $form['email'], PDO::PARAM_STR);
    // $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    // $stmt->bindParam(':image_id', $image_id, PDO::PARAM_INT);
    // $success = $stmt->execute();
  if (!$success) {
    die($db->error);
  }
  unset($_SESSION['form']);
  header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>教員登録確認</title>

  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員登録確認</h1>
    </div>

    <div id="content">
      <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
      <form action="" method="post">
        <dl>
          <dt>姓</dt>
          <dd><?php echo h($form['last_name']); ?></dd>
          <dt>名</dt>
          <dd><?php echo h($form['first_name']); ?></dd>
          <dt>メールアドレス</dt>
          <dd><?php echo h($form['email']); ?></dd>
          <dt>パスワード</dt>
          <dd>
            【表示されません】
          </dd>
          <dt>顔写真</dt>
          <dd>
            <img src="../teacher_pictures/<?php echo h($form['image']); ?>" width="100" alt="" />
          </dd>
        </dl>
        <div><a href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
      </form>
    </div>

  </div>
</body>

</html>