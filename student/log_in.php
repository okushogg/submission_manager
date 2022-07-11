<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');
$error = [];
$email = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password');
  if ($email === '' || $password === '') {
    $error['login'] = 'blank';
  } else {
    //  ログイン情報チェック
    $stmt = $db->prepare('select * from students where email=:email limit 1');
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $student_info = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($student_info && $student_info['is_active'] == 1) {
      if (password_verify($password, $student_info['password'])) {
        // ログイン成功
        session_regenerate_id();
        $_SESSION['auth']['login'] = true;
        $_SESSION['auth']['student_id'] = $student_info['id'];
        $_SESSION['auth']['last_name'] = $student_info['last_name'];
        $_SESSION['auth']['first_name'] = $student_info['first_name'];
        $_SESSION['auth']['student_image_id'] = $student_info['image_id'];
        header('Location: home.php');
        exit();
      } else {
        // ログイン失敗
        $error['login'] = 'failed';
      }
    } else {
      // ログイン失敗
      $error['login'] = 'failed';
    }
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="../style.css" />
  <title>生徒ログインページ</title>
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒ログインページ</h1>
    </div>
    <div id="content">
      <div id="lead">
        <p>メールアドレスとパスワードを記入してログインしてください。</p>
        <p>登録がまだの方はこちらからどうぞ。</p>
        <p>&raquo;<a href="sign_up.php">登録手続きをする</a></p>
        <p>パスワードをお忘れの方はこちら。</p>
        <p>&raquo;<a href="reset_password.php">パスワードの再設定</a></p>
      </div>
      <form action="" method="post">
        <dl>
          <dt>メールアドレス</dt>
          <dd>
            <input type="text" name="email" size="35" maxlength="255" value="<?php echo h($email); ?>" />
            <?php if (isset($error['login']) && $error['login'] === 'blank') : ?>
              <p class="error">* メールアドレスとパスワードをご記入ください</p>
            <?php endif; ?>
            <?php if (isset($error['login']) && $error['login'] === 'failed') : ?>
              <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
            <?php endif; ?>
          </dd>
          <dt>パスワード</dt>
          <dd>
            <input type="password" name="password" size="35" maxlength="255" value="<?php echo h($password); ?>" />
          </dd>
        </dl>
        <div>
          <input type="submit" value="ログインする" />
        </div>
      </form>
      <div style="text-align: right; margin: 10px;"><a href="../teacher/log_in.php">>教員用ログインページ</a></div>

    </div>
  </div>
</body>

</html>