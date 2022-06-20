<?php
session_start();
require('../dbconnect.php');
require('../libs.php');

// フォームの中身を確認、内容がなければ初期化
if (isset($_GET['action']) && isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  $form = [
    'first_name' => '',
    'last_name' => '',
    'grade' => 0,
    'class' => 0,
    'email' => '',
    'password' => '',
    'is_active' => true
  ];
}

// エラーの初期化
$error = [];


// フォームの内容をチェック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 姓名の確認
  $form['first_name'] = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
  if ($form['first_name'] === '') {
    $error['first_name'] = 'blank';
  }

  $form['last_name'] = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
  if ($form['last_name'] === '') {
    $error['last_name'] = 'blank';
  }

  //メールアドレスが入力されているかチェック
  $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  if ($form['email'] === '') {
    $error['email'] = 'blank';
  } else {
    // 同一のメールアドレスがないかチェック
    $stmt = $db->prepare('select count(*) from students where email=?');
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(1, $form['email'], PDO::PARAM_STR);
    // $success = $stmt->execute(array($form['email']));
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $cnt_string = $stmt->fetch(PDO::FETCH_COLUMN);
    $cnt = intval($cnt_string);
    // var_dump($cnt);

    if ($cnt > 0) {
      $error['email'] = 'duplicate';
    }
  }
}

// パスワードのチェック
$form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
if ($form['password'] === '') {
  $error['password'] = 'blank';
} elseif (strlen($form['password']) < 4) {
  $error['password'] = 'length';
}

// 学年とクラスのチェック
$form['grade'] = filter_input(INPUT_POST, 'grade', FILTER_SANITIZE_NUMBER_INT);
if ($form['grade'] == 0) {
  $error['grade'] = 'blank';
}
$form['class'] = filter_input(INPUT_POST, 'class', FILTER_SANITIZE_NUMBER_INT);
if ($form['class'] == 0) {
  $error['class'] = 'blank';
}

if (empty($error)) {
  $_SESSION['form'] = $form;

  // 画像のアップロード
  if ($image['name'] !== '') {
    $filename = date('Ymdhis') . '_' . $image['name'];
    if (!move_uploaded_file($image['tmp_name'], '../student_pictures/' . $filename)) {
      die('ファイルのアップロードに失敗しました');
    }
    $_SESSION['form']['image_path'] = $filename;
  } else {
    $_SESSION['form']['image_path'] = '';
  }

  header('Location: check.php');
  exit();
}


// 本年度のクラスを求める
$year = (new \DateTime('-3 month'))->format('Y');
$stmt = $db->prepare("select id, grade, class from classes where year=:year");
if (!$stmt) {
  die($db->error);
}
$stmt->bindParam(':year', $year, PDO::PARAM_STR);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$this_year_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($this_year_classes);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>生徒登録</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>

  <body>
    <div id="wrap">
      <div id="head">
        <h1>生徒登録</h1>
      </div>

      <div id="content">
        <p>次のフォームに必要事項をご記入ください。</p>
        <form action="" method="post" enctype="multipart/form-data">
          <dl>
            <dt>姓<span class="required">（必須）</span></dt>
            <?php if (isset($error['last_name']) && $error['first_name'] === 'blank') : ?>
              <p class="error">* 苗字を入力してください</p>
            <?php endif; ?>
            <dd>
              <input type="text" name="last_name" size="35" maxlength="255" value="<?php echo h($form['last_name']); ?>" />
            </dd>

            <dt>名<span class="required">（必須）</span></dt>
            <?php if (isset($error['first_name']) && $error['first_name'] === 'blank') : ?>
              <p class="error">* 名前を入力してください</p>
            <?php endif; ?>
            <dd>
              <input type="text" name="first_name" size="35" maxlength="255" value="<?php echo h($form['first_name']); ?>" />
            </dd>

            <dt>クラス<span class="required">（必須）</span></dt>
            <?php if (isset($error['class']) && $error['class'] === 'blank') : ?>
              <p class="error">* クラスを入力してください</p>
            <?php endif; ?>
            <dd>
              <select size="1" name="class_id">
                <option value="0">-</option>
                <?php foreach ($this_year_classes as $class) : ?>
                  <option value="<?php $class['id']; ?>"><?php print "{$class['grade']} - {$class['class']}"; ?></option>
                <?php endforeach; ?>
              </select>
            </dd>

            <dt>メールアドレス<span class="required">（必須）</span></dt>
            <?php if (isset($error['email']) && $error['email'] === 'blank') : ?>
              <p class="error">* メールアドレスを入力してください</p>
            <?php endif; ?>
            <dd>
              <input type="text" name="email" size="35" maxlength="255" value="<?php echo h($form['email']); ?>" />

            <dt>パスワード<span class="required">（必須）</span></dt>
            <dd>
              <input type="password" name="password" size="10" maxlength="20" value="<?php echo h($form['password']); ?>" />
            </dd>
            <?php if (isset($error['password']) && $error['password'] === 'blank') : ?>
              <p class="error">* パスワードを入力してください</p>
            <?php elseif (isset($error['password']) && $error['password'] === 'length') : ?>
              <p class="error">* パスワードは4文字以上で入力してください</p>
            <?php endif; ?>

            <dt>写真など</dt>
            <dd>
              <input type="file" name="image" size="35" value="" />
              <?php if (isset($error['image']) && $error['image'] === 'type') : ?>
                <p class="error">* 写真などは「.png」または「.jpg」の画像を指定してください</p>
                <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
              <?php endif; ?>
            </dd>

            <dd>
              <input type="hidden" name="is_active" value=true />
            </dd>
          </dl>
          <div><input type="submit" value="入力内容を確認する" /></div>
        </form>
      </div>
  </body>

</html>