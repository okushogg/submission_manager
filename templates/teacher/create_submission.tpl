<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>教員 課題作成ページ</title>
  <link rel="stylesheet" href="../style.css" />
  <script type="text/javascript"  src="../private/js/create_submission.js"></script>
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員 課題作成ページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>
      {* ユーザーの情報表示 *}
      {include file="/Applications/MAMP/htdocs/submissions_manager/templates/teacher/teacher_info_display.tpl"}

      <div>
        <form action="" method="post" enctype="multipart/form-data">
          <dl>
            <dt>課題名<span class="required">（必須）</span></dt>
            {if isset($error.submission_name) && $error.submission_name === 'blank'}
              <p class="error">* 課題名を入力してください</p>
            {/if}
            <dd>
              <input type="text" name="submission_name" size="35" maxlength="255" value="{$form.submission_name}" />
            </dd>

            <dt>クラス<span class="required">（必須）</span></dt>
            {if isset($error.class_id) && $error.class_id === 'blank'}
              <p class="error">* クラスを入力してください</p>
            {/if}
            <dd>
              <div id="class_select">
                <select id="class_0" size="1" name="class_id">
                  <option value="0">-</option>
                  {foreach $classes_info as $class}
                    {if $form.class_id == $class.id}
                      <option value={$class.id} selected> {$class.grade} - {$class.class}</option>
                    {else}
                      <option value={$class.id}> {$class.grade} - {$class.class}</option>
                    {/if}
                  {/foreach}
                </select>
              </div>
            </dd>

            <div style="margin-top: 10px; margin-bottom: 10px;">
              <input type="button" value="フォーム追加" onclick="addForm()">
            </div>

            <dt>教科<span class="required">（必須）</span></dt>
            {if isset($error.subject_id) && $error.subject_id === 'blank'}
              <p class="error">* 教科を入力してください</p>
            {/if}
            <dd>
              <select size="1" name="subject_id">
                <option value="0">-</option>
                {foreach $all_subjects as $subject}
                  {if $form.subject_id == $subject.id}
                    <option value="{$subject.id}" selected> {$subject.name} </option>
                  {else}
                    echo "<option value="{$subject.id}"> {$subject.name} </option>
                  {/if}
                {/foreach}
              </select>
            </dd>

            <dt>提出期限<span class="required">（必須）</span></dt>
             {if isset($error.dead_line) && $error.dead_line === 'blank'}
              <p class="error">* 提出期限を入力してください</p>
             {elseif isset($error.dead_line) && $error.dead_line === 'not_future_date'}
              <p class="error">* 提出期限は本日以降の日付を入力してください</p>
             {/if}
            <dd>
              <input type="date" name="dead_line" value="{$form.dead_line}" />
            </dd>

            <dd>
              <input type="hidden" name="teacher_id" value={$teacher_info.teacher_id} />
            </dd>
          </dl>
          <div><input type="submit" value="課題を作成" /></div>
        </form>
      </div>

      <script type="text/javascript" src="../submission.js"></script>
</body>

</html>