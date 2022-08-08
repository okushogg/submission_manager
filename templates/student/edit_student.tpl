<!DOCTYPE html>
<html lang="ja">

{* header *}
{include file="../common/header.tpl"}

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒情報編集ページ</h1>
    </div>

    <div id="content">
      {if isset($smarty.session.auth.teacher_id)}
        <div style="text-align: right"><a href="../teacher/home.php">教員ホームへ</a></div>
      {/if}
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>
      <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
      <form action="" method="post" enctype="multipart/form-data">
        <dl>
          <dt>氏</dt>
          {if isset($error.last_name) && $error.first_name === 'blank'}
            <p class="error">* 苗字を入力してください</p>
          {/if}
          <dd>
            <input type="text" name="last_name" size="35" maxlength="255" value="{$student_info.last_name}" />
          </dd>

          <dt>名</dt>
          {if isset($error.first_name) && $error.first_name === 'blank'}
            <p class="error">* 名前を入力してください</p>
          {/if}
          <dd>
            <input type="text" name="first_name" size="35" maxlength="255" value="{$student_info.first_name}" />
          </dd>
          <dt>性別</dt>
          {if isset($smarty.session.auth.teacher_id)}
            {if isset($error.sex) && $error.sex === null}
              <p class="error">* 性別を入力してください</p>
            {/if}
            <dd>
              <input type="radio" name="sex" value=0 {if $student_info.sex === "0"} checked{/if}>男
              <input type="radio" name="sex" value=1 {if $student_info.sex === "1"} checked{/if}>女
            </dd>
          {else}
            <dd>
              <input type="hidden" name="sex" value="{$student_info.sex}"/>
              {display_sex($student_info.sex)}
            </dd>
          {/if}

          <dt>クラス</dt>
          {if isset($error.class_id) && $error.class_id === 'blank'}
            <p class="error">* クラスを入力してください</p>
          {/if}
          <dd>
            <select size="1" name="class_id">
              <option value="0">-</option>
              {foreach $selectable_classes as $class}
                {if $class.id == $this_year_class.class_id}
                  <option value="{$class.id}" selected> {$class.grade} - {$class.class}</option>
                {else}
                  <option value="{$class.id}"> {$class.grade} - {$class.class}</option>
                {/if}
              {/foreach}
            </select>
            {if $this_year > $this_year_class.year}
              <span class="required">要新規登録</span>
            {/if}
          </dd>

          <dt>出席番号</dt>
          {if isset($error.student_num) && $error.student_num === 'blank'}
            <p class="error">* 出席番号を入力してください</p>
          {/if}
          <dd>
            {if $this_year > $this_year_class.year}
              <input type="number" min="1" max="40" name="student_num" /><span class="required">要新規登録</span>
            {else}
              <input type="number" min="1" max="40" name="student_num" value="{$this_year_class.student_num}" />
            {/if}
          </dd>

          <dt>メールアドレス</dt>
          {if isset($error.email) && $error.email === 'blank'}
            <p class="error">* メールアドレスを入力してください</p>
          {elseif isset($error.email) && $error.email === 'duplicate'}
            <p class="error">* 登録済のメールアドレスです</p>
          {/if}
          <dd>
            <input type="text" name="email" size="35" maxlength="255" value="{$student_info.email}");
          </dd>
          <dt>パスワード</dt>
          <dd>
            <p>パスワードの変更は<a href="../student/reset_password.php">こちら</a>から。</p>
          </dd>

          {if isset($smarty.session.auth.teacher_id) || $this_year > $this_year_class.year}
            <dt>写真など</dt>
            <dd>
              <input type="file" name="image" size="35" value="" />
              {if isset($error.image) && $error.image === 'type'}
                <p class="error">* 写真などは「.png」または「.jpg」の画像を指定してください</p>
                <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
              {elseif isset($error.image) && $error.image === 'size'}
                <p class="error">* 500KB以下の画像を指定してください </p>
              {/if}
            </dd>
          {/if}



          {if isset($smarty.session.auth.teacher_id)}
            <dt>在籍情報</dt>
            <dd>
              <input type="radio" name="is_active" value=0 {if $student_info.is_active == 0} checked {/if}> 除籍
              <input type="radio" name="is_active" value=1 {if $student_info.is_active == 1} checked {/if}> 在籍
            </dd>
          {else}
            <input type="hidden" name="is_active" value="{$student_info.is_active}" />
          {/if}
        </dl>

        {if isset($error.edit_check) && $error.edit_check === 'no_edit'}
          <p class="error">* 編集内容がありません</p>
        {/if}
        <div><input type="submit" value="生徒情報を更新" /></div>
    </div>

  </div>
</body>

</html>