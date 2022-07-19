
<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>教員トップページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員トップページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="search_student.php">生徒検索</a></div>
      <div style="text-align: right"><a href="register_class.php">クラス登録</a></div>
      <div style="text-align: right"><a href="create_submission.php">提出物登録</a></div>
      <div style="text-align: left">
        <img src="../teacher_pictures/{$pic_info.path}" width="100" height="100" alt="" />
        {$teacher_info.last_name|cat: $teacher_info.first_name|cat:"先生"}
      </div>

      <div>
        <div class="box">
          {foreach $classes_array.1 as $a}
            <div class="box">
              <a href="index_submission.php?class_id={$a.id}">{$a.grade}-{$a.class}</a>
            </div>
          {/foreach}
        </div>

        <div class="box">
          {foreach $classes_array.2 as $a}
            <div class="box">
              <a href="index_submission.php?class_id={$a.id}">{$a.grade}-{$a.class}</a>
            </div>
          {/foreach}
        </div>

        <div class="box">
          {foreach $classes_array.3 as $a}
            <div class="box">
              <a href="index_submission.php?class_id={$a.id}">{$a.grade}-{$a.class}</a>
            </div>
          {/foreach}
        </div>
      </div>


</body>

</html>