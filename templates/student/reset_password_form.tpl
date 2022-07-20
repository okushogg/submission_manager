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
          {if isset($error.password) && $error.password === 'blank'}
            <p class="error">* パスワードを入力してください</p>
          {elseif isset($error.password) && $error.password === 'length'}
            <p class="error">* パスワードは4文字以上で入力してください</p>
          {/if}
          <dd>
            <input type="password" name="password" size="10" maxlength="20" value="{$form.password}" />
          </dd>
          <input type="submit" value="送信" />
      </form>
    </div>

  </div>
</body>

</html>

</html>