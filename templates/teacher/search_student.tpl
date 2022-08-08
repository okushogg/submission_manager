<!DOCTYPE html>
<html lang="jp">

{* header *}
{include file="../common/header.tpl"}

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒検索ページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>
    {* ユーザーの情報表示 *}
    {include file="../teacher/teacher_info_display.tpl"}
      <p>生徒検索</p>
      <form action="" method="post">
        <span>年度</span>
        <select size="1" name="year">
          {foreach $all_years as $y}
            {if $y.year == $form.year}
              <option value="{$y.year}" selected> {$y.year} </option>
            {else}
              <option value="{$y.year}"> {$y.year} </option>
            {/if}
          {/foreach}
        </select>
        <span>学年</span>
        <select size="1" name="grade">
          {foreach $grades as $key => $value}
            {if $value == $form.grade}
              <option value={$value} selected> {$key} </option>
            {else}
              <option value={$value}> {$key} </option>
            {/if}
          {/foreach}
        </select>
        <span>クラス</span>
        <select size="1" name="class">
          {foreach $classes as $key => $value}
            {if $value == $form.class}
              <option value="{$value}" selected> {$key} </option>
            {else}
              <option value="{$value}"> {$key} </option>
            {/if}
          {/foreach}
        </select>
        <input type="radio" name="is_active" value=0 {if $form.is_active === "0"}'checked'{/if}>除籍
        <input type="radio" name="is_active" value=1 {if $form.is_active === "1"}'checked'{/if}>在籍
        <br>
        <span>氏</span>
        <input type="text" name="last_name" size="20" maxlength="20" value="{$form.last_name}" />
        <span>名</span>
        <input type="text" name="first_name" size="20" maxlength="20" value="{$form.first_name}" />
        <input type="submit" value="検索" />
      </form>
      {if $_POST}
      {if count($student_search_result)>0}
        <!-- 生徒検索結果一覧 -->
        <div style="margin: 15px;">
          <table class="" style="text-align: center;">
            <tr>
              <th>学年</th>
              <th>クラス</th>
              <th>出席番号</th>
              <th>氏名</th>
              <th>在籍状況</th>
            </tr>
            <tr>
            {foreach $student_search_result as $student}
              <!-- 学年 -->
              <td>
                {$student.grade}
              </td>
              <!-- クラス -->
              <td>
                {$student.class}
              </td>
              <!-- 出席番号 -->
              <td>
                {$student.student_num}
              </td>
              <!-- 生徒氏名 -->
              <td>
                <a href="../student/home.php?student_id={$student.student_id}">
                  {$student.last_name} {$student.first_name}
                </a>
              </td>
              <!-- 在籍状況 -->
              {if $student.is_active == 0}
                <td style="color: red;">
                  除籍済
                </td>
              {else}
                <td>
                  在籍
                </td>
              {/if}
            </tr>
            {/foreach}
          </table>
        </div>
      {else}
         <p>該当する生徒はいませんでした。</p>
      {/if}
      {/if}


</body>

</html>