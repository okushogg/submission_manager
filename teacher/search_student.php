<?php
session_start();
require('../dbconnect.php');
require('../libs.php');

$form = [
  "last_name" => '',
  "first_name" => '',
  "year" => '',
  "grade" => '',
  "class" => ''
];

$grades = [
  "-" => 0,
  "1" => 1,
  "2" => 2,
  "3" => 3
];

$classes = [
  "-" => 0,
  "A" => "A",
  "B" => "B",
  "C" => "C"
];

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

// 登録されている年度を全て取得
$all_years = get_years($db);

// 検索ボタン押下時
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $form['year'] = filter_input(INPUT_POST, 'year');
  $form['grade'] = filter_input(INPUT_POST, 'grade');
  $form['class'] = filter_input(INPUT_POST, 'class');
  $form['last_name'] = filter_input(INPUT_POST, 'last_name');
  $form['first_name'] = filter_input(INPUT_POST, 'first_name');

  $sql = "SELECT students.id as student_id, students.first_name, students.last_name, students.sex,
                   belongs.class_id, classes.year, classes.grade,
                   classes.class, belongs.student_num, students.is_active
              FROM belongs
         LEFT JOIN students ON belongs.student_id = students.id
         LEFT JOIN classes ON belongs.class_id = classes.id";
  $sql .= " WHERE ";
  $sql .= 'classes.year = "' . $form['year'] . '"';

  // 学年が選択されている場合
  if ($form['grade'] != 0) {
    $sql .= " AND ";
    $sql .= 'classes.grade = "' . $form['grade'] . '"';
  }

  //クラスが選択されている場合
  if ($form['class'] != 0) {
    $sql .= " AND ";
    $sql .= 'classes.class = "' . $form['class'] . '"';
  }

  //氏が記入されている場合
  if ($form['last_name'] != '') {
    $sql .= " AND ";
    $sql .= 'students.last_name LIKE "%' . $form['last_name'] . '%"';
  }

  //名が記入されている場合
  if ($form['first_name'] != '') {
    $sql .= " AND ";
    $sql .= 'students.first_name LIKE "%' . $form['first_name'] . '%"';
  }

  $stmt = $db->query($sql);
  // var_dump($sql);
  $student_search_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // var_dump($student_search_result);
}
?>

<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>生徒検索ページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒検索ページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>
      <div style="text-align: left">
        <img src="../teacher_pictures/<?php echo h($pic_info['path']); ?>" width="100" height="100" alt="" />
        <?php echo $last_name ?> <?php echo $first_name . ' 先生' ?>
      </div>

      <p>生徒検索</p>
      <form action="" method="post">
        <span>年度</span>
        <select size="1" name="year">
          <?php
          foreach ($all_years as $y) {
            if ($y['year'] == $this_year) {
              echo "<option value={$y['year']} selected>" . $y['year'] . "</option>";
            } else {
              echo "<option value={$y['year']}>" . $y['year'] . "</option>";
            }
          }
          ?>
        </select>

        <span>学年</span>
        <select size="1" name="grade">
          <?php
          foreach ($grades as $key => $value) {
            if ($value == $form['grade']) {
              echo "<option value=$value selected>" . $key . "</option>";
            } else {
              echo "<option value=$value>" . $key . "</option>";
            }
          }
          ?>
        </select>

        <span>クラス</span>
        <select size="1" name="class">
        <?php
          foreach ($classes as $key => $value) {
            if ($value == $form['class']) {
              echo "<option value=$value selected>" . $key . "</option>";
            } else {
              echo "<option value=$value>" . $key . "</option>";
            }
          }
          ?>
        </select>
        <br>
        <span>氏</span>
        <input type="text" name="last_name" size="20" maxlength="20" value="<?php echo $form['last_name']; ?>" />
        <span>名</span>
        <input type="text" name="first_name" size="20" maxlength="20" value="<?php $form['first_name']; ?>" />
        <input type="submit" value="検索" />
      </form>

      <?php if (isset($student_search_result)) : ?>

        <!-- 生徒検索結果一覧 -->
        <div style="margin: 15px;">
          <table class="" style="text-align: center;">
            <tr>
              <th>学年</th>
              <th>クラス</th>
              <th>出席番号</th>
              <th>氏名</th>
              <th>在籍状況</th>
            </tr>

            <?php foreach ($student_search_result as $student) : ?>

              <!-- 学年 -->
              <td>
                <?php echo h($student['grade']); ?>
              </td>

              <!-- クラス -->
              <td>
                <?php echo h($student['class']); ?>
              </td>

              <!-- 出席番号 -->
              <td>
                <?php echo $student['student_num']; ?>
              </td>

              <!-- 生徒氏名 -->
              <td>
                <a href="../student/home.php?student_id=<?php echo h($student['student_id']); ?>">
                  <?php echo $student['last_name'] . $student['first_name']; ?>
                </a>
              </td>

              <!-- 在籍状況 -->
              <?php if ($student['is_active'] == 0) : ?>
                <td style="color: red;">
                  除籍済
                </td>
              <?php else : ?>
                <td>
                  在籍
                </td>
              <?php endif; ?>
              </tr>
            <?php endforeach; ?>
          </table>
        </div>

      <?php endif; ?>


</body>

</html>