<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

{* header *}
{include file="../common/header.tpl"}

<body>
  <div id="wrap">
    <div id="head">
      <h1>確認ページ</h1>
    </div>
    <div id="content">
      <div id="lead">
       <a href="javascript:history.back()">前に戻る</a><br>
       <a href="../student/log_in.php">生徒ログインページへ</a>
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
          <input type="submit" value="教員ページへ" />
        </div>
      </form>
    </div>
  </div>
</body>

</html>