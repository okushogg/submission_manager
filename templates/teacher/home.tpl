
<!DOCTYPE html>
<html lang="jp">

{* header *}
{include file="../common/header.tpl"}

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
      {include file="../teacher/teacher_info_display.tpl"}

      {if $classes_array}

      {if isset($classes_array.1)}
        <ul>
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
        </ul>
      {else}
      <p>新年度のクラスが未登録です。</p>
      {/if}
</body>

</html>