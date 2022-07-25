<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>生徒トップページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒トップページ</h1>
    </div>
    <div id="content">
     {if isset($teacher_id) }
        <div style="text-align: right"><a href="../teacher/home.php">教員ホームへ</a></div>
     {/if}

      <div style="text-align: right">
       {if (!$this_year_class) }
          <span class="required">要新規登録</span>
       {/if}
        <a href="edit_student.php">生徒情報編集ページ</a>
      </div>

      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: left">

        <!-- 所属クラス -->
        <div style="margin-top: 10px; margin-bottom: 10px;">
          <p>所属クラス</p>
          <form action="" method="post">
            <select size="1" name="year">
             {foreach $belonged_classes as $belonged_class}
                {if $belonged_class.year == $form.year}
                  <option value="{$belonged_class.year}" selected>
                    {$belonged_class.year}年度{$belonged_class.grade}年{$belonged_class.class}組
                  </option>
                {else}
                  <option value="{$belonged_class.year}">
                    {$belonged_class.year}年度{$belonged_class.grade}年{$belonged_class.class}組
                  </option>
                {/if}
              {/foreach}
            </select>
            <input type="submit" value="変更" />
          </form>
        </div>
        <!-- 生徒情報 -->
        <div>
          <img src="../student_pictures/{$student_pic_info.path}" width="100" height="100" alt="" />
         {if $this_year_class }
           {$this_year_class.grade}-{$this_year_class.class} No_{$this_year_class.student_num}
         {/if}
           {$student_info.student_last_name} {$student_info.student_first_name}さん
         {if $student_info.is_active == 0 }
            <p style="color: red;">除籍済</p>
         {/if}
        </div>

       {if $this_year_class }
          <div>
            <p> 各教科 課題一覧</p>
            {foreach $all_subjects as $subject }
              <span style="margin: 15px;">
                <a href="index_submission.php?subject_id={$subject.subject_id}&class_id={$class_id}">
                 {$subject.name}
                </a>
              </span>
            {/foreach}
          </div>

         {if $submission_info }
            <!-- 課題一覧 -->
            <div style="margin: 15px;">
              <table class="" style="text-align: center;">
                <tr>
                  <!-- <th>h_id</th> -->
                  <th>教科</th>
                  <th>課題名</th>
                  <th>提出期限</th>
                  <th>受領日</th>
                  <th>評価</th>
                </tr>
               {foreach $submission_info as $submission }
                  <!-- 教科 -->
                  <td>
                   {$all_subjects[$submission.subject_id].name}
                  </td>

                  <!-- 課題名 -->
                  <td>
                   {$submission.submission_name}
                  </td>

                  <!-- 提出期限 -->
                  <td>
                   {$submission.dead_line}
                  </td>

                  <!-- 受領日 -->
                  <td>
                   {$submission.approved_date}
                  </td>

                  <!-- スコア -->
                 {if $submission.score === "0"}
                    <td style="color: red;">
                     {$scoreList[$submission.score]}
                    </td>
                 {else}
                    <td>
                     {$scoreList[$submission.score]}
                    </td>
                 {/if}
                  </tr>
               {/foreach}
              </table>
           {else}
              <p>期限の近い課題はありません</p>
           {/if}
        {/if}
</body>

</html>