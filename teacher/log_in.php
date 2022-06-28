<?php
session_start();
require('../dbconnect.php');
require('../libs.php');
$error = [];
$email = '';
$password = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  if ($email === '' || $password === '') {
    $error['login'] = 'blank';
  } else {
    //  ログイン情報チェック
    $stmt = $db->prepare('select * from teachers where email=:email limit 1');
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    $teacher_info = $stmt->fetch(PDO::FETCH_ASSOC);
    if($teacher_info){
      if (password_verify($password, $teacher_info['password'])) {
        // ログイン成功
        session_regenerate_id();
        $_SESSION['teacher_id'] = $teacher_info['id'];
        $_SESSION['last_name'] = $teacher_info['last_name'];
        $_SESSION['first_name'] = $teacher_info['first_name'];
        $_SESSION['image_id'] = $teacher_info['image_id'];
        header('Location: home.php');
        exit();
      } else {
        // ログイン失敗
        $error['login'] = 'failed';
      }
    } else {
      $error['login'] = 'failed';
    }

    // var_dump($teacher_info['password']);
  }

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="../style.css" />
  <title>教員ログインページ</title>
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員ログインページ</h1>
    </div>
    <div id="content">
      <div id="lead">
        <p>メールアドレスとパスワードを記入してログインしてください。</p>
        <p>登録がまだの方はこちらからどうぞ。</p>
        <p>&raquo;<a href="sign_up.php">登録手続きをする</a></p>
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
    </div>
  </div>
</body>

</html>