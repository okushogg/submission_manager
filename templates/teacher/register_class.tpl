<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>クラス登録</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>クラス登録</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">トップページへ</a></div>
      {* ユーザーの情報表示 *}
      {include file="../teacher/teacher_info_display.tpl"}
      
      <form action="" method="post">
        <dl>
          <dt>年度</dt>
          <dd>
            {$this_year}年度
          </dd>

          <dt>学年</dt>
          {if isset($error.grade)}
            <p class="error">* 学年を入力してください。</p>
          {/if}
          <dd>
            <select name="grade">
              {foreach $grades as $key => $value}
                {if $value == $form['grade']}
                  <option value="{$value}" selected>{$key}</option>
                {else}
                  <option value="{$value}">{$key}</option>
              {/if}
              {/foreach}
            </select>
          </dd>

          <dt>クラス</dt>
          {if isset($error.class) && $error.class === 'blank'}
            <p class="error">* クラスを入力してください。</p>
          {elseif isset($error.class) && $error.class === 'same_class'}
            <p class="error">* 登録済のクラスです。</p>
          {/if}
          <dd>
            <select name="class">
              {foreach $classes as $key => $value}
                {if $value == $form['class']}
                  <option value="{$value}" selected> {$key} </option>
                {else}
                  <option value="{$value}"> {$key} </option>
                {/if}
              {/foreach}
            </select>
          </dd>
        </dl>
        <div>
          <input type="submit" value="登録" />
        </div>
      </form>
    </div>
  </div>


</body>

</html>