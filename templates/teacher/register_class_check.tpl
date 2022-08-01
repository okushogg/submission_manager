<!DOCTYPE html>
<html lang="ja">

{* header *}
{include file="../common/header.tpl"}

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員 クラス登録確認</h1>
    </div>

    <div id="content">
      <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
      <form action="" method="post">
        <dl>
          <dt>登録クラス</dt>
          <dd>{$this_year}年度 {$form.grade}年 {$form.class}組</dd>
        </dl>
        <div><a href="register_class.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
      </form>
    </div>

  </div>
</body>

</html>