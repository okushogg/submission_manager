<!DOCTYPE html>
<html lang="jp">

{* header *}
{include file="../common/header.tpl"}

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員 課題編集ページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>
      {* ユーザーの情報表示 *}
      {include file="../teacher/teacher_info_display.tpl"}

      <div>
        <form action="" method="post" enctype="multipart/form-data">
          <dl>
            <dt>課題名</dt>
            {if isset($error.submission_name) && $error.submission_name === 'blank'}
              <p class="error">* 課題名を入力してください</p>
            {/if}
            <dd>
              <input type="text" name="submission_name" size="35" maxlength="255" value="{$submission_info.name}" />
            </dd>

            <dt>クラス</dt>
            <dd>
              {$submission_info.grade} - {$submission_info.class}
            </dd>

            <dt>教科</dt>
            {if isset($error.subject_id) && $error.subject_id === 'blank'}
              <p class="error">* 教科を入力してください</p>
            {/if}
            <dd>
              <select size="1" name="subject_id">
                <option value="0">-</option>
                {foreach $all_subjects as $subject}
                  {if $submission_info.subject_name === $subject.name}
                    <option value={$subject.subject_id} selected> {$subject.name} </option>
                {else}
                    <option value={$subject.subject_id}> {$subject.name} </option>
                {/if}
                {/foreach}
              </select>
            </dd>

            <dt>提出期限</dt>
            {if isset($error.dead_line) && $error.dead_line === 'blank'}
              <p class="error">* 提出期限を入力してください</p>
            {elseif isset($error.dead_line) && $error.dead_line === 'not_future_date'}
              <p class="error">* 提出期限は本日以降の日付を入力してください</p>
            {/if}
            <dd>
              <input type="date" name="dead_line" value="{$submission_info.dead_line}" />
            </dd>

            <dd>
              <input type="hidden" name="teacher_id" value=$id />
            </dd>
          </dl>
          <div><input type="submit" value="課題を編集" /></div>
        </form>

      </div>
</body>

</html>