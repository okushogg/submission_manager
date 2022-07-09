<?php
session_start();
require('../dbconnect.php');
require('../libs.php');

// フォームの中身を確認、内容がなければ初期化
if (isset($_GET['action']) && isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  $form = [
    'submission_name' => '',
    'class_id' => '',
    'subject_id' => '',
    'dead_line' => '',
    'teacher_id' => $_SESSION['teacher_id'],
  ];
}

// エラーの初期化
$error = [];

// 今日の日付
$today = date('Y-m-d');

// ログイン情報がないとログインページへ移る
if (isset($_SESSION['teacher_id']) && isset($_SESSION['last_name']) && isset($_SESSION['first_name'])) {
  $teacher_id = $_SESSION['teacher_id'];
  $last_name = $_SESSION['last_name'];
  $first_name = $_SESSION['first_name'];
  $image_id = $_SESSION['teacher_image_id'];
} else {
  header('Location: log_in.php');
  exit();
}

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
// var_dump($pic_info);

// 該当年度の年度のクラスを取得する
$classes_stmt = $db->prepare("select id, year, grade, class from classes where year=:year");
$classes_stmt->bindParam(':year', $this_year, PDO::PARAM_STR);
$classes_stmt->execute();
$classes_info = $classes_stmt->fetchAll(PDO::FETCH_ASSOC);
$cnt = count($classes_info);
// var_dump($classes_info);

// 教科一覧
$subjects_stmt = $db->prepare("SELECT id, name FROM subjects");
$subjects_stmt->execute();
$all_subjects = $subjects_stmt->fetchAll(PDO::FETCH_ASSOC);


//「課題を作成する」をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 課題名の確認
  $form['submission_name'] = filter_input(INPUT_POST, 'submission_name', FILTER_SANITIZE_STRING);
  if ($form['submission_name'] === '') {
    $error['submission_name'] = 'blank';
  }

  // クラスの確認
  $form['class_id'] = filter_input(INPUT_POST, 'class_id', FILTER_SANITIZE_NUMBER_INT);
  $class_id = intval($form['class_id']);
  if ($form['class_id'] == 0) {
    $error['class_id'] = 'blank';
  }

  // 教科の確認
  $form['subject_id'] = filter_input(INPUT_POST, 'subject_id', FILTER_SANITIZE_NUMBER_INT);
  $subject_id = intval($form['subject_id']);
  if ($form['subject_id'] == 0) {
    $error['subject_id'] = 'blank';
  }

  // 提出期限の確認
  $form['dead_line'] = filter_input(INPUT_POST, 'dead_line', FILTER_SANITIZE_NUMBER_INT);
  $dead_line = $form['dead_line'];
  if ($form['dead_line'] === '') {
    $error['dead_line'] = 'blank';
  } elseif ($today > $dead_line) {
    $error['dead_line'] = 'not_future_date';
  }

  // hiddenに入ったteacherのid
  $teacher_id = intval($form['teacher_id']);

  // 指定されたclass_idを持つ全てのstudent_idを求める(除籍済を除く)
  $student_stmt = $db->prepare("SELECT b.student_id as student_id, b.student_num as student_num
                                FROM belongs AS b
                                INNER JOIN students AS s
                                ON b.student_id = s.id
                                WHERE class_id = :class_id AND s.is_active = 1
                                ORDER BY b.student_num");
  $student_stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
  $student_success = $student_stmt->execute();
  if(!$student_success){
    die($db->error);
  }
  $all_student_id = $student_stmt->fetchAll(PDO::FETCH_ASSOC);


  // 入力に問題がない場合
  if (empty($error)) {
    $_SESSION['form'] = $form;
    // submissionsレコードを作成
    $stmt = $db->prepare("INSERT INTO submissions(name,
                                                    class_id,
                                                    subject_id,
                                                    dead_line,
                                                    teacher_id)
                                            VALUES(:name,
                                                   :class_id,
                                                   :subject_id,
                                                   :dead_line,
                                                   :teacher_id)");
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindValue(':name', $form['submission_name'], PDO::PARAM_STR);
    $stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->bindValue(':subject_id', $subject_id, PDO::PARAM_INT);
    $stmt->bindValue(':dead_line', $dead_line, PDO::PARAM_STR);
    $stmt->bindValue(':teacher_id', $teacher_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }

    // 作成したsubmissionsレコードに紐付く該当クラス全生徒のstudent_submissionsレコードを作成
    $submission_id = $db->lastInsertId();
    foreach ($all_student_id as $student_id) {
      $submission_stmt = $db->prepare("INSERT INTO student_submissions(student_id,
                                                                         submission_id)
                                                                  VALUES(:student_id,
                                                                         :submission_id)");
      if (!$submission_stmt) {
        die($db->error);
      }
      $submission_stmt->bindValue(':student_id', $student_id['student_id'], PDO::PARAM_INT);
      $submission_stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
      $submission_success = $submission_stmt->execute();
      if (!$submission_success) {
        die($db->error);
      }
    }
    header('Location: home.php');
    exit();
  }
  // var_dump($form);
}
?>

<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>教員 課題作成ページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員 課題作成ページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>
      <div style="text-align: left">
        <img src="../teacher_pictures/<?php echo h($pic_info['path']); ?>" width="100" height="100" alt="" />
        <?php echo $last_name ?> <?php echo $first_name . ' 先生' ?>
      </div>

      <div>
        <form action="" method="post" enctype="multipart/form-data">
          <dl>
            <dt>課題名<span class="required">（必須）</span></dt>
            <?php if (isset($error['submission_name']) && $error['submission_name'] === 'blank') : ?>
              <p class="error">* 課題名を入力してください</p>
            <?php endif; ?>
            <dd>
              <input type="text" name="submission_name" size="35" maxlength="255" value="<?php echo h($form['submission_name']); ?>" />
            </dd>

            <dt>クラス<span class="required">（必須）</span></dt>
            <?php if (isset($error['class_id']) && $error['class_id'] === 'blank') : ?>
              <p class="error">* クラスを入力してください</p>
            <?php endif; ?>
            <dd>
              <div id="class_select">
              <select id="class_0" size="1" name="class_id">
                <option value="0">-</option>
                <?php
                foreach ($classes_info as $class) {
                  if ($form['class_id'] == $class['id']) {
                    echo "<option value={$class['id']} selected> {$class['grade']} - {$class['class']}</option>";
                  } else {
                    echo "<option value={$class['id']}> {$class['grade']} - {$class['class']}</option>";
                  }
                }
                ?>
              </select>
              </div>
            </dd>
            <!-- <div style="margin-top: 10px; margin-bottom: 10px; padding: 2px;">
              <input  type="button" value="クラス追加" onclick="addForm()">
            </div> -->

            <dt>教科<span class="required">（必須）</span></dt>
            <?php if (isset($error['subject_id']) && $error['subject_id'] === 'blank') : ?>
              <p class="error">* 教科を入力してください</p>
            <?php endif; ?>
            <dd>
              <select size="1" name="subject_id">
                <option value="0">-</option>
                <?php
                foreach ($all_subjects as $subject) {
                  if ($form['subject_id'] == $subject['id']) {
                    echo "<option value={$subject['id']} selected> {$subject['name']} </option>";
                  } else {
                    echo "<option value={$subject['id']}> {$subject['name']} </option>";
                  }
                }
                ?>
              </select>
            </dd>

            <dt>提出期限<span class="required">（必須）</span></dt>
            <?php if (isset($error['dead_line']) && $error['dead_line'] === 'blank') : ?>
              <p class="error">* 提出期限を入力してください</p>
            <?php elseif (isset($error['dead_line']) && $error['dead_line'] === 'not_future_date') : ?>
              <p class="error">* 提出期限は本日以降の日付を入力してください</p>
            <?php endif; ?>
            <dd>
              <input type="date" name="dead_line" value="<?php echo h($form['dead_line']); ?>" />
            </dd>

            <dd>
              <input type="hidden" name="teacher_id" value=$id />
            </dd>
          </dl>
          <div><input type="submit" value="課題を作成" /></div>
        </form>
      </div>

<script type="text/javascript" src="../submission.js"></script>
</body>

</html>