<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

// 現在のバンコクの時刻
$current_time = bkk_time();

// urlからpassword_reset_tokenを取得
$password_reset_token = filter_input(INPUT_GET, 'password_reset_token');

// フォームの初期化
$form = [
  "password" => ''
];

// エラーの初期化
$error = [];

// パスワード再設定をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // パスワードのチェック
  $form['password'] = filter_input(INPUT_POST, 'password');
  if ($form['password'] === '') {
    $error['password'] = 'blank';
  } elseif (strlen($form['password']) < 4) {
    $error['password'] = 'length';
  }

  if (empty($error)) {
    // フォームに入力されたパスワードをハッシュ化
    $password = password_hash($form['password'], PASSWORD_DEFAULT);

    // password_reset_tokenが一致しているstudentのpasswordを変更
    $stmt = $db->prepare("UPDATE students
                           SET password = :password,
                               updated_at = :updated_at,
                               password_reset_token = null
                         WHERE password_reset_token = :password_reset_token");
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);
    $stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
    $stmt->bindValue(':password_reset_token', $password_reset_token, PDO::PARAM_STR);

    $success = $stmt->execute();
    if (!$success) {
      $db->error;
    }
    header("Location: log_in.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>生徒用パスワードリセットフォーム</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒用パスワードリセットフォーム</h1>
    </div>
    <div id="content">
      <p>新しいパスワードをご入力ください。</p>
      <form action="" method="post">
        <dl>
          <dt>新しいパスワード</dt>
          <dd>
            <input type="password" name="password" size="10" maxlength="20" value="<?php echo h($form['password']); ?>" />
          </dd>
          <?php if (isset($error['password']) && $error['password'] === 'blank') : ?>
            <p class="error">* パスワードを入力してください</p>
          <?php elseif (isset($error['password']) && $error['password'] === 'length') : ?>
            <p class="error">* パスワードは4文字以上で入力してください</p>
          <?php endif; ?>
          <input type="submit" value="送信" />
      </form>
    </div>

  </div>
</body>

</html>

</html>