<!DOCTYPE html>
<html lang="ja">

{* header *}
{include file="../common/header.tpl"}

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員登録確認</h1>
    </div>

    <div id="content">
      <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
      <form action="" method="post">
        <dl>
          <dt>姓</dt>
          <dd>{$form.last_name}</dd>
          <dt>名</dt>
          <dd>{$form.first_name}</dd>
          <dt>メールアドレス</dt>
          <dd>{$form.email}</dd>
          <dt>パスワード</dt>
          <dd>
            【表示されません】
          </dd>
          <dt>顔写真</dt>
          <dd>
            {if $form.image !== '' }
              <img src="../teacher_pictures/{$form.image}" width="100" alt="" />
            {else}
              <img src="../teacher_pictures/no_image.jpg" width="100" alt="" />
            {/if}
          </dd>
        </dl>
        <div><a href="sign_up.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
      </form>
    </div>

  </div>
</body>

</html>