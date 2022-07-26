
<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>教員トップページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員トップページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="search_student.php">生徒検索</a></div>
      <div style="text-align: right"><a href="register_class.php">クラス登録</a></div>
      <div style="text-align: right"><a href="create_submission.php">提出物登録</a></div>
      {* ユーザーの情報表示 *}
      {include file="/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl"}

      {if $classes_array}
      
      {if isset($classes_array.1)}
      <div>
        <div class="box">
          {foreach $classes_array.1 as $a}
            <div class="box">
              <a href="index_submission.php?class_id={$a.id}">{$a.grade}-{$a.class}</a>
            </div>
          {/foreach}
        </div>
      {else}
        <li>新年度の1年生クラスは未登録です。</li>
      {/if}
       
       {if isset($classes_array.2)}
        <div class="box">
          {foreach $classes_array.2 as $a}
            <div class="box">
              <a href="index_submission.php?class_id={$a.id}">{$a.grade}-{$a.class}</a>
            </div>
          {/foreach}
        </div>
        {else}
          <li>新年度の２年生クラスは未登録です。</li>
        {/if}

        {if isset($classes_array.3)}
        <div class="box">
          {foreach $classes_array.3 as $a}
            <div class="box">
              <a href="index_submission.php?class_id={$a.id}">{$a.grade}-{$a.class}</a>
            </div>
          {/foreach}
        </div>
        {else}
          <li>新年度の3年生クラスは未登録です。</li>
        {/if}
      </div>
      
      {else}
      <p>新年度のクラスが未登録です。</p>
      {/if}


</body>

</html>