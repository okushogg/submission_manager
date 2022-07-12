<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

// 現在の時刻
$current_time = bkk_time();

// フォームの中身を初期化
$form = [
  'submission_name' => '',
  'subject_id' => '',
  'dead_line' => '',
  'teacher_id' => $_SESSION['auth']['teacher_id'],
];

// エラーの初期化
$error = [];

// submission_id
$submission_id = filter_input(INPUT_GET, 'submission_id', FILTER_SANITIZE_NUMBER_INT);

// 今日の日付
$today = date('Y-m-d');

// ログイン情報がないとログインページへ移る
login_check();

// 教員がログインしていた場合
$teacher_id = $_SESSION['auth']['teacher_id'];
$image_id = $_SESSION['auth']['teacher_image_id'];

// 画像の情報を取得
$stmt = $db->prepare("select path from images where id=:id");
if (!$stmt) {
  die($db->error);
}
$stmt->bindParam(':id', $image_id, PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$pic_info = $stmt->fetch(PDO::FETCH_ASSOC);


// 教科一覧
$subjects_stmt = $db->prepare("SELECT id, name FROM subjects");
$subjects_stmt->execute();
$all_subjects = $subjects_stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($all_subjects);

// 課題の情報を求める
$submission_stmt = $db->prepare("SELECT submissions.name, submissions.dead_line,
                                        subjects.id as subject_id,
                                        subjects.name as subject_name,
                                        submissions.class_id,
                                        classes.grade, classes.class
                                   FROM submissions
                                   LEFT JOIN subjects
                                   ON submissions.subject_id = subjects.id
                                   LEFT JOIN classes
                                   ON submissions.class_id = classes.id
                                   WHERE submissions.id = :submission_id");
if (!$submission_stmt) {
  die($db->error);
}
$submission_stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
$success = $submission_stmt->execute();
if (!$success) {
  die($db->error);
}
$submission_info = $submission_stmt->fetch(PDO::FETCH_ASSOC);
$class_id = $submission_info['class_id'];


//「課題内容を編集」をクリックしたら
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 課題名の確認
  $form['submission_name'] = filter_input(INPUT_POST, 'submission_name');
  if ($form['submission_name'] === '') {
    $error['submission_name'] = 'blank';
  }

  // 教科の確認
  $form['subject_id'] = filter_input(INPUT_POST, 'subject_id', FILTER_SANITIZE_NUMBER_INT);
  $subject_id = intval($form['subject_id']);
  if ($form['subject_id'] == 0) {
    $error['subject_id'] = 'blank';
  }

  // 提出期限の確認
  $form['dead_line'] = filter_input(INPUT_POST, 'dead_line', FILTER_SANITIZE_NUMBER_INT);
  // 提出期限を変更する場合は過去の日付を選べない
  if ($form['dead_line'] != $submission_info['dead_line']) {
    if ($form['dead_line'] === '') {
      $error['dead_line'] = 'blank';
    } elseif ($today > $form['dead_line']) {
      $error['dead_line'] = 'not_future_date';
    }
  }

  // teacherのid
  $teacher_id = $form['teacher_id'];

  // 入力に問題がない場合
  if (empty($error)) {
    // submissionsを編集
    $stmt = $db->prepare("UPDATE submissions
                          SET name = :submission_name,
                              subject_id = :subject_id,
                              dead_line = :dead_line,
                              teacher_id = :teacher_id,
                              updated_at = :updated_at
                        WHERE id = :submission_id");
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindValue(':submission_name', $form['submission_name'], PDO::PARAM_STR);
    $stmt->bindValue(':subject_id', $form['subject_id'], PDO::PARAM_INT);
    $stmt->bindValue(':dead_line', $form['dead_line'], PDO::PARAM_STR);
    $stmt->bindValue(':teacher_id', $form['teacher_id'], PDO::PARAM_INT);
    $stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
    $stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }
    header("Location: index_submission.php?class_id={$class_id}");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>教員 課題編集ページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員 課題編集ページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>
      <div style="text-align: left">
        <img src="../teacher_pictures/<?php echo h($pic_info['path']); ?>" width="100" height="100" alt="" />
        <?php echo $_SESSION['auth']['last_name'] ?> <?php echo $_SESSION['auth']['first_name'] . ' 先生' ?>
      </div>

      <div>
        <form action="" method="post" enctype="multipart/form-data">
          <dl>
            <dt>課題名</dt>
            <?php if (isset($error['submission_name']) && $error['submission_name'] === 'blank') : ?>
              <p class="error">* 課題名を入力してください</p>
            <?php endif; ?>
            <dd>
              <input type="text" name="submission_name" size="35" maxlength="255" value="<?php echo h($submission_info['name']); ?>" />
            </dd>

            <dt>クラス</dt>
            <dd>
              <?php echo "{$submission_info['grade']} - {$submission_info['class']}"; ?>
            </dd>

            <dt>教科</dt>
            <?php if (isset($error['subject_id']) && $error['subject_id'] === 'blank') : ?>
              <p class="error">* 教科を入力してください</p>
            <?php endif; ?>
            <dd>
              <select size="1" name="subject_id">
                <option value="0">-</option>
                <?php
                foreach ($all_subjects as $subject) {
                  if ($submission_info['subject_id'] == $subject['id']) {
                    echo "<option value={$subject['id']} selected> {$subject['name']} </option>";
                  } else {
                    echo "<option value={$subject['id']}> {$subject['name']} </option>";
                  }
                }
                ?>
              </select>
            </dd>

            <dt>提出期限</dt>
            <?php if (isset($error['dead_line']) && $error['dead_line'] === 'blank') : ?>
              <p class="error">* 提出期限を入力してください</p>
            <?php elseif (isset($error['dead_line']) && $error['dead_line'] === 'not_future_date') : ?>
              <p class="error">* 提出期限は本日以降の日付を入力してください</p>
            <?php endif; ?>
            <dd>
              <input type="date" name="dead_line" value="<?php echo h($submission_info['dead_line']); ?>" />
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