<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>生徒登録</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>

  <body>
    <div id="wrap">
      <div id="head">
        <h1>生徒登録</h1>
      </div>

      <div id="content">
        <p>&raquo;<a href="log_in.php">ログインページ</a></p>
        <p>次のフォームに必要事項をご記入ください。</p>
        <form action="" method="post" enctype="multipart/form-data">
          <dl>
            <dt>氏<span class="required">（必須）</span></dt>
            {if isset($error.last_name) && $error.last_name === 'blank'}
              <p class="error">* 苗字を入力してください</p>
            {elseif isset($error.last_name) && $error.last_name === 'invalid_letter' }
              <p class="error">* 全角ひらがな、カタカナ、漢字で入力してください</p>
            {/if}
            <dd>
              <input type="text" name="last_name" size="35" maxlength="255" value="{$form.last_name}" />
            </dd>

            <dt>名<span class="required">（必須）</span></dt>
            {if isset($error.first_name) && $error.first_name === 'blank' }
              <p class="error">* 名前を入力してください</p>
            {elseif isset($error.first_name) && $error.first_name === 'invalid_letter' }
              <p class="error">* 全角ひらがな、カタカナ、漢字で入力してください</p>
            {/if }
            <dd>
              <input type="text" name="first_name" size="35" maxlength="255" value="{$form.first_name}" />
            </dd>

            <dt>性別<span class="required">（必須）</span></dt>
            {if isset($error.sex) && $error.sex === 'blank'}
              <p class="error">* 性別を入力してください</p>
            {/if}
            <dd>
              <input type="radio" name="sex" value=0 {if $form.sex === "0"} checked {/if}>男
              <input type="radio" name="sex" value=1 {if $form.sex === "1"} checked {/if}>女
            </dd>

            <dt>クラス<span class="required">（必須）</span></dt>
            {if isset($error.class_id) && $error.class_id === 'blank'}
              <p class="error">* クラスを入力してください</p>
            {/if}
            <dd>
              <select size="1" name="class_id">
                <option value="0">-</option>
                {foreach $this_year_classes as $class}
                  {if $form.class_id == $class.id}
                   <option value="{$class.id}" selected> {$class.grade}-{$class.class}</option>
                  {else}
                   <option value="{$class.id}"> {$class.grade}-{$class.class}</option>
                  {/if}
                {/foreach}
              </select>
            </dd>

            <dt>出席番号<span class="required">（必須）</span></dt>
            {if isset($error.student_num) && $error.student_num === 'blank'}
              <p class="error">* 出席番号を入力してください</p>
            {/if}
            <dd>
              <input type="number" min="1" max="40" name="student_num" value="{$form.student_num}" />
            </dd>

            <dt>メールアドレス<span class="required">（必須）</span></dt>
            {if isset($error.email) && $error.email === 'blank'}
              <p class="error">* メールアドレスを入力してください</p>
            {elseif isset($error['email']) && $error.email === 'not_like_email' }
              <p class="error">* メールアドレスの形式ではないようです</p>
            {elseif isset($error['email']) && $error.email === 'duplicate' }
              <p class="error">* 登録済のメールアドレスです</p>
            {/if}
            <dd>
              <input type="text" name="email" size="35" maxlength="255" value="{$form.email}" />
            </dd>

            <dt>パスワード<span class="required">（必須）</span></dt>
            {if isset($error.password) && $error.password === 'blank'}
              <p class="error">* パスワードを入力してください</p>
            {elseif isset($error.password) && $error.password === 'length'}
              <p class="error">* パスワードは4文字以上で入力してください</p>
            {elseif isset($error.password) && $error.password === 'invalid_letter'}
              <p class="error">* パスワードは半角英数字で入力してください</p>
            {/if}
            <dd>
              <input type="password" name="password" size="10" maxlength="20" value="{$form.password}" />
            </dd>

            <dt>写真など</dt>
            <dd>
              {if isset($error.image) && $error.image === 'type'}
                <p class="error">* 写真などは「.png」または「.jpg」の画像を指定してください</p>
                <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
              {elseif isset($error.image) && $error.image === 'size'}
                <p class="error">* 500KB以下の画像を指定してください </p>
              {/if}
              <input type="file" name="image" size="35" value="" />
            </dd>

            <dd>
              <input type="hidden" name="is_active" value=true />
            </dd>
          </dl>
          <div><input type="submit" value="入力内容を確認する" /></div>
        </form>
      </div>
  </body>

</html>