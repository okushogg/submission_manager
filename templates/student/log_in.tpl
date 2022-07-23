<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="../style.css" />
  <title>生徒ログインページ</title>
  <script type="text/javascript" src="../js/teacher_page.js"></script>
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
            <input type="text" name="email" size="35" maxlength="255" value="{$email}" />
            {if isset($error.login) && $error.login === 'blank'}
              <p class="error">* メールアドレスとパスワードをご記入ください</p>
            {elseif isset($error.login) && $error.login === 'failed'}
              <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
            {/if}
          </dd>
          <dt>パスワード</dt>
          <dd>
            <input type="password" name="password" size="35" maxlength="255" value="{$password}" />
          </dd>
        </dl>
        <div>
          <input type="submit" value="ログインする" />
        </div>
      </form>

      <form style="text-align: right; margin: 10px;">
        <input type="button" value="教員用ログインページ" onclick="myEnter()">
      </form>
    </div>
  </div>
</body>

</html>