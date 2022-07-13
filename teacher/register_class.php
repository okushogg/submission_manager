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

$grades = [
  "-" => 0,
  "1" => 1,
  "2" => 2,
  "3" => 3
];

$classes = [
  "-" => '-',
  "A" => 'A',
  "B" => 'B',
  "C" => 'C'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 学年入力チェック
  $grade = filter_input(INPUT_POST, 'grade', FILTER_SANITIZE_NUMBER_INT);
  if ($grade === '0') {
    $error['grade'] = 'blank';
  }
  // クラス入力チェック
  $class = filter_input(INPUT_POST, 'class', FILTER_SANITIZE_STRING);
  if ($class === '-') {
    $error['class'] = 'blank';
  }

  // 同じクラスがないかチェック
  $stmt_check = $db->prepare("SELECT * FROM classes WHERE year = ? AND grade = ? AND class = ?");
  $success_check = $stmt_check->execute(array($this_year, $grade, $class));
  if (!$success_check) {
    die($db->error);
  }
  $same_class_check = $stmt_check->fetchALL(PDO::FETCH_ASSOC);
  if (count($same_class_check) >= 1) {
    $error['class'] = 'same_class';
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
  <title>クラス登録</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>クラス登録</h1>
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
          <?php if (isset($error['grade'])) : ?>
            <p class="error">* 学年を入力してください。</p>
          <?php endif; ?>
          <dd>
            <select name="grade">
              <?php
              foreach ($grades as $key => $value) {
                if ($value == $grade) {
                  echo "<option value=$value selected>" . $key . "</option>";
                } else {
                  echo "<option value=$value>" . $key . "</option>";
                }
              }
              ?>
            </select>
          </dd>

          <dt>クラス</dt>
          <?php if (isset($error['class']) && $error['class'] === 'blank') : ?>
            <p class="error">* クラスを入力してください。</p>
          <?php elseif (isset($error['class']) && $error['class'] === 'same_class') : ?>
            <p class="error">* 登録済のクラスです。</p>
          <?php endif; ?>
          <dd>
            <select name="class">
              <?php
              foreach ($classes as $key => $value) {
                if ($value == $class) {
                  echo "<option value=$value selected>" . $key . "</option>";
                } else {
                  echo "<option value=$value>" . $key . "</option>";
                }
              }
              ?>
            </select>
          </dd>
        </dl>
        <div>
          <input type="submit" value="登録" />
        </div>
      </form>
    </div>
  </div>


</body>

</html>