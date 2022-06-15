<?php
session_start();
require('../libs.php');

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

  // Emailのチェック
  $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  if ($form['email'] === '') {
    $error['email'] = 'blank';
  } else {
    $db = dbconnect();
    $stmt = $db->prepare('select count(*) from students where email=?');
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bind_param('s', $form['email']);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }

    $stmt->bind_result($cnt);
    $stmt->fetch();
  }

  // パスワードのチェック
  $form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  if ($form['password'] === '') {
    $error['password'] = 'blank';
  } elseif (strlen($form['password']) < 4) {
    $error['password'] = 'length';
  }

  // 学年・クラスのチェック
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
}
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

            <dt>学年<span class="required">（必須）</span></dt>
            <?php if (isset($error['grade']) && $error['grade'] === 'blank') : ?>
              <p class="error">* 学年を入力してください</p>
            <?php endif; ?>
            <dd>
              <select name="grade">
                <option value="0">-</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
            </dd>

            <dt>クラス<span class="required">（必須）</span></dt>
            <?php if (isset($error['class']) && $error['class'] === 'blank') : ?>
              <p class="error">* クラスを入力してください</p>
            <?php endif; ?>
            <dd>
              <select name="class">
                <option value="0">-</option>
                <option value="1">A</option>
                <option value="2">B</option>
                <option value="3">C</option>
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