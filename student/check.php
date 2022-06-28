<?php
session_start();
require('../libs.php');
require('../dbconnect.php');

// 直接check.phpに飛ばないようにする
if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  header('Location: sign_up.php');
  exit();
}

// class_idから学年クラスを表示する
$stmt = $db->prepare("select grade, class from classes where id=:id");
if (!$stmt) {
  die($db->error);
}
$stmt->bindParam(':id', $form['class_id'], PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$my_class = $stmt->fetch(PDO::FETCH_ASSOC);

// 登録内容を確定
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
    $get_image_id = $db->prepare("select id from images where path = '" . $form['image'] . "'");
    $get_image_id->execute();
    $image_id_str = $get_image_id->fetch(PDO::FETCH_COLUMN);
    $image_id = intval($image_id_str);
    unset($stmt);
  } else {
    // 画像がない場合はlibsに指定した$no_image_idを使用
    $image_id = $no_image_id;
  }

  // パスワードをDBに直接保管しない
  $password = password_hash($form['password'], PASSWORD_DEFAULT);
  // 情報をテーブルに保存
  $stmt = $db->prepare('insert into students(last_name, first_name, email, password, image_id, sex) values(?, ?, ?, ?, ?, ?)');
  if (!$stmt) {
    die($db->error);
  }
  $success = $stmt->execute(array($form['last_name'], $form['first_name'], $form['email'], $password, $image_id, $form['sex']));
  if (!$success) {
    die($db->error);
  }

  // 作成したstudentsテーブルのレコードからstudent_idを求める
  $stmt_student = $db->prepare('select id from students where email=:email');
  if (!$stmt_student) {
    die($db->error);
  }
  $stmt_student->bindParam(':email', $form['email'], PDO::PARAM_STR);
  $success_student = $stmt_student->execute();
  if (!$success_student) {
    die($db->error);
  }
  $student_id_str = $stmt_student->fetch(PDO::FETCH_COLUMN);
  $student_id = intval($student_id_str);

  // 所属クラスと出席番号の情報をbelongsテーブルに保存
  $class_id_int=intval($form['class_id']);
  $student_num_int=intval($form['student_num']);
  $stmt_belongs = $db->prepare('insert into belongs(student_id, class_id, student_num) values(?, ?, ?)');
  if (!$stmt_belongs) {
    die($db->error);
  }
  $success_belongs =$stmt_belongs->execute(array($student_id,$class_id_int,$student_num_int));
  if (!$success_belongs) {
    die($db->error);
  }

  // セッションにstudentの情報を入れる
  $_SESSION['student_id'] = $student_id;
  $_SESSION['last_name'] = $form['last_name'];
  $_SESSION['first_name'] = $form['first_name'];
  $_SESSION['student_image_id'] = $image_id;

  // セッション内のフォーム内容を破棄してstudent/home.phpへ
  unset($_SESSION['form']);
  header('Location: home.php');
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>生徒登録確認</title>

  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒登録確認</h1>
    </div>

    <div id="content">
      <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
      <form action="" method="post">
        <dl>
          <dt>姓</dt>
          <dd><?php echo h($form['last_name']); ?></dd>
          <dt>名</dt>
          <dd><?php echo h($form['first_name']); ?></dd>
          <dt>性別</dt>
          <dd><?php echo h($form['sex']); ?></dd>
          <dt>クラス</dt>
          <dd><?php echo "{$my_class['grade']} - {$my_class['class']}"; ?></dd>
          <dt>メールアドレス</dt>
          <dd><?php echo h($form['email']); ?></dd>
          <dt>パスワード</dt>
          <dd>
            【表示されません】
          </dd>
          <dt>顔写真</dt>
          <dd>
            <img src="../student_pictures/<?php echo h($form['image']); ?>" width="100" alt="" />
          </dd>
        </dl>
        <div><a href="sign_up.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
      </form>
    </div>

  </div>
</body>

</html>