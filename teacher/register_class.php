<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');
$error = [];


// ログイン情報がないとログインページへ移る
login_check();

// 教員がログインしていた場合
$teacher_id = $_SESSION['auth']['teacher_id'];
$image_id = $_SESSION['auth']['teacher_image_id'];

// 画像の情報を取得
$stmt = $db->prepare("select path from images where id=:id");
if (!$stmt) {
  die($db->error);
}
$stmt->bindParam(':id', $image_id, PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$pic_info = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($pic_info);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 学年入力チェック
  $grade = filter_input(INPUT_POST, 'grade', FILTER_SANITIZE_NUMBER_INT);
  if ($grade === '0') {
    $error['grade'] = 'blank';
  }
  // クラス入力チェック
  $class = filter_input(INPUT_POST, 'class', FILTER_SANITIZE_STRING);
  if ($class === '0') {
    $error['class'] = 'blank';
  }

  // 入力に問題がなければ
  if (empty($error)) {
    $stmt = $db->prepare("insert into classes(year, grade, class) values(?, ?, ?)");
    if (!$stmt) {
     die($db->error);
   }
   $success = $stmt->execute(array($this_year, $grade, $class));
   if (!$success) {
     die($db->error);
   }
   header('Location: home.php');
   }
}
?>

<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>教員トップページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員トップページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">トップページへ</a></div>
      <div style="text-align: left">
        <img src="../teacher_pictures/<?php echo h($pic_info['path']); ?>" width="48" height="48" alt="" />
        <?php echo $_SESSION['auth']['last_name'] ?> <?php echo $_SESSION['auth']['first_name'] . ' 先生' ?>
      </div>

      <form action="" method="post">
        <dl>
          <dt>年度</dt>
          <dd>
            <?php echo $this_year . '年度' ?>
          </dd>

          <dt>学年</dt>
          <dd>
            <select name="grade">
              <option value=0>-</option>
              <option value=1>1</option>
              <option value=2>2</option>
              <option value=3>3</option>
            </select>
          </dd>
          <?php if (isset($error['grade'])) : ?>
              <p class="error">* 学年を入力してください。</p>
          <?php endif; ?>

          <dt>クラス</dt>
          <dd>
            <select name="class">
              <option value="0">-</option>
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="C">C</option>
            </select>
          </dd>
          <?php if (isset($error['class'])) : ?>
              <p class="error">* クラスを入力してください。</p>
          <?php endif; ?>
        </dl>
        <div>
          <input type="submit" value="登録" />
        </div>
      </form>
    </div>
  </div>


</body>

</html>