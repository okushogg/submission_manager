<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>課題入力ページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>評価入力ページ</h1>
    </div>


    <div id="content">
      <!-- ナビゲーション -->
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="edit_submission.php?submission_id={$submission_id}">課題編集</a></div>
      <div style="text-align: right"><a href="delete_submission.php?submission_id={$submission_id}">課題削除</a></div>
      <div style="text-align: right"><a href="index_submission.php?class_id={$submission_info.class_id}">課題一覧へ</a></div>

      <!-- ユーザー情報 -->
      {* ユーザーの情報表示 *}
      {include file="/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl"}

      <!-- 課題情報 -->
      <div>
        <h3>{$submission_info.grade}-{$submission_info.class}</h3>
        <h3>{$submission_info.subject_name}</h3>
        <h1>{$submission_info.name}</h1>
      </div>

      <!-- 生徒一覧 -->
      <div>
        <form action="" , method="post">
          <table class="">
            <tr>
              <!-- <th>h_id</th> -->
              <th>No.</th>
              <th>生徒名</th>
              <th>提出期限</th>
              <th>受領日</th>
              <th>評価</th>
            </tr>
            {foreach $students_who_have_submission as $student}
              <!-- 出席番号 -->
              <td>
                {$student_num_array[{$student.student_id}].student_num}
              </td>

              <!-- 生徒名 -->
              <td>
                <a href="../student/home.php?student_id={$student.student_id}">
                  {$student.last_name}{$student.first_name}
                </a>
              </td>

              <!-- 提出期限 -->
              {if $student.dead_line <= $today && $student.score == null || 0}
                <td style="color: red;">
                  {$student.dead_line}
                </td>
              {else}
                <td>
                  {$student.dead_line}
                </td>
              {/if}

              <!-- 受領日 -->
              <td>
                {$student.approved_date}
              </td>

              <!-- スコア -->
              <td>
                <select size="1" name="score[{$student.student_submissions_id}]">
                  {foreach $scoreList as $key => $value}
                    {$student_score_int = intval($student.score)}
                    {if isset($student.score) && $value === $student_score_int}
                      <option value="{$value}" selected>{$key} </option>
                    {else}
                      <option value="{$value}">{$key}</option>
                    {/if}
                  {/foreach}
                  ?>
                </select>
              </td>
              </tr>
            {/foreach}
          </table>
          <div><input type="submit" value="評価を更新" /></div>
        </form>
      </div>


</body>

</html>