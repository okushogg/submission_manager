<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{$all_subjects[$subject_id].name}課題一覧</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>{$all_subjects[$subject_id].name}課題一覧</h1>
    </div>
    <div id="content">
      {if isset($teacher_id) }
        <div style="text-align: right"><a href="../teacher/home.php">教員ホームへ</a></div>
      {/if}
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php?year={$chosen_class.year}">ホーム</a></div>
      <div style="text-align: left">
        <div style="margin: 10px">
          <p>クラス</p>
            <div style="display: flex">
              {$chosen_class.year}年度{$chosen_class.grade}年{$chosen_class.class}組
            </div>
        </div>
        <img src="../student_pictures/{$student_pic_info.path}" width="100" height="100" alt="" />
        {$chosen_class.grade} - {$chosen_class.class} No_{$chosen_class.student_num}
        {$student_info.student_last_name} {$student_info.student_first_name}さん
      </div>

      <!-- 課題一覧 -->
      <div>
        {if $submission_info}
          <form action="" , method="post">
            <table class="" style="text-align: center">
              <tr>
                <!-- <th>h_id</th> -->
                <th>課題名</th>
                <th>提出期限</th>
                <th>受領日</th>
                <th>評価</th>
              </tr>
              {foreach $submission_info as $submission}

                <!-- student_submissions_id -->
                <!-- <td>
                {$submission.student_submissions_id}
              </td> -->

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
                <td>
                  {$scoreList[$submission.score]}
                </td>
                </tr>
              {/foreach}
            </table>
          {else}
            <p>課題はありません</p>
          {/if}


</body>

</html>