<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{$class_info.grade} - {$class_info.class} 課題一覧ページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>{$class_info.grade} - {$class_info.class} 課題一覧ページ</h1>
    </div>
    <div id="content">
      <!-- ナビゲーション -->
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="create_submission.php">課題作成</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>

      <!-- ユーザー情報 -->
      {* ユーザーの情報表示 *}
      {include file="/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl"}

      <!-- 課題一覧 -->
      <div>
      {if $submission_info}
        <table class="">
          <tr>
            <th>課題名</th>
            <th>教科名</th>
            <th>提出期限</th>
          </tr>
          {foreach $submission_info as $submission}
            <tr>
              <td>
                <a href="show_submission.php?submission_id={$submission.id}">
                  {$submission.submission_name}
                </a>
              </td>
              <td>{$submission.subject_name}</td>
              <td>{$submission.dead_line}</td>
            </tr>
          {/foreach}
        </table>
        {else}
            <p>課題はありません</p>
        {/if}
      </div>


</body>

</html>