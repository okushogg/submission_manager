<!DOCTYPE html>
<html lang="jp">

{* header *}
{include file="../common/header.tpl"}

<body>
  <div id="wrap">
    <div id="head">
      <h1>{$all_subjects[$subject_id].name}課題一覧</h1>
    </div>
    <div id="content">
      {if isset($smarty.session.auth.teacher_id) }
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
        {$student_info.last_name} {$student_info.first_name}さん
      </div>

      <!-- 課題一覧 -->
      <div>
        {if $submission_info}
            <table class="" style="text-align: center">
              <tr>
                <!-- <th>h_id</th> -->
                <th>課題名</th>
                <th>提出期限</th>
                <th>受領日</th>
                <th>評価</th>
              </tr>

              {foreach $submission_info as $submission}

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
      </div>


</body>

</html>