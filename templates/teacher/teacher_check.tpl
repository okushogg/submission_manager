<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="../style.css" />
  <title>確認ページ</title>
  <script type="text/javascript" src="../private/js/teacher_page.js"></script>
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>確認ページ</h1>
    </div>
    <div id="content">
      <div id="lead">
       <a href="javascript:history.back()">前に戻る</a>
      </div>
      <form action="" method="post">
        <dl>
          <dt>パスワード</dt>
          {if isset($error.teacher_check)}
            <p class="error">* 正しいパスワードを入力してください。</p>
          {/if}
          <dd>
            <input type="password" name="password" size="35" maxlength="255" value="{$password}" />
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