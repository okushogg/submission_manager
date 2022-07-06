<?php
session_start();
require('../libs.php');
require('../dbconnect.php');

// 今年度
$this_year = (new \DateTime('-3 month'))->format('Y');

// 今日の日付
$today = date('Y-m-d');

// 現在の時刻
$current_time = bkk_time();

// ログイン情報のない生徒、教員は編集ページに入れない
if (isset($_SESSION['teacher']) || isset($_SESSION['student_id'])) {
  $student_id = intval($_SESSION['student_id']);
} else {
  header('Location: log_in.php');
  exit();
}

// フォームの初期化
$form = [
  'first_name' => '',
  'last_name' => '',
  'sex' => '',
  'class_id' => '',
  'student_num' => '',
  'email' => '',
  'image' => '',
  'is_active' => 1
];

// 生徒情報を取得
$stmt = $db->prepare("SELECT first_name, last_name, sex, email, image_id, is_active
                      FROM students
                      WHERE id=:student_id");
if (!$stmt) {
  die($db->error);
}
$stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$student_info = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($student_info);

// 現在の所属クラスを求める
$this_year_class_stmt = $db->prepare("SELECT belongs.id as belongs_id, belongs.class_id, belongs.student_num as student_num,
                                             classes.class as class, classes.grade as grade, classes.year as year
                                      FROM belongs
                                      LEFT JOIN classes
                                      ON belongs.class_id = classes.id
                                      WHERE belongs.student_id = :student_id
                                      ORDER BY classes.year DESC");
$this_year_class_stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
$this_year_class_stmt->execute();
$this_year_class = $this_year_class_stmt->fetch(PDO::FETCH_ASSOC);
$class_id = $this_year_class['class_id'];
// var_dump($this_year_class);

//現在在籍する学年から選択可能なクラスを求める
if ($this_year > $this_year_class['year']) {
  $sql = "SELECT id, grade, class FROM classes WHERE year=:year AND grade > :this_year_class";
} else {
  $sql = "SELECT id, grade, class FROM classes WHERE year=:year AND grade = :this_year_class";
}

$stmt = $db->prepare($sql);
if (!$stmt) {
  die($db->error);
}
$stmt->bindParam(':year', $this_year, PDO::PARAM_STR);
$stmt->bindParam(':this_year_class', $this_year_class['grade'], PDO::PARAM_STR);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$selectable_classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
var_dump($current_time);




// 生徒情報を更新をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 姓名の確認
  $form['first_name'] = filter_input(INPUT_POST, 'first_name');
  if ($form['first_name'] === '') {
    $error['first_name'] = 'blank';
  }

  $form['last_name'] = filter_input(INPUT_POST, 'last_name');
  if ($form['last_name'] === '') {
    $error['last_name'] = 'blank';
  }

  //メールアドレスが入力されているかチェック
  $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  if ($form['email'] === '') {
    $error['email'] = 'blank';
  }

  // 画像のチェック
  $image = $_FILES['image'];
  if ($image['name'] !== '' && $image['error'] === 0) {
    $type = mime_content_type($image['tmp_name']);
    if ($type !== 'image/png' && $type !== 'image/jpeg') {
      $error['image'] = 'type';
    }
  }

  // 学年とクラスのチェック
  $form['class_id'] = filter_input(INPUT_POST, 'class_id', FILTER_SANITIZE_NUMBER_INT);
  if ($form['class_id'] == 0) {
    $error['class_id'] = 'blank';
  }

  // 出席番号のチェック
  $form['student_num'] = filter_input(INPUT_POST, 'student_num', FILTER_SANITIZE_NUMBER_INT);
  if ($form['student_num'] == 0) {
    $error['student_num'] = 'blank';
  }


  // 性別のチェック
  $form['sex'] = filter_input(INPUT_POST, 'sex');
  if ($form['sex'] === '') {
    $error['sex'] = 'blank';
  }

  // 在籍状況のチェック
  $form['is_active'] = filter_input(INPUT_POST, 'is_active');

  if (empty($error)) {
    // var_dump($form);
    // var_dump($this_year_class['belongs_id']);
    // 画像のアップロード
    if ($image['name'] !== '') {
      $filename = date('Ymdhis') . '_' . $image['name'];
      if (!move_uploaded_file($image['tmp_name'], '../student_pictures/' . $filename)) {
        die('ファイルのアップロードに失敗しました');
      }
      $_SESSION['form']['image'] = $filename;
    } else {
      $_SESSION['form']['image'] = '';
    }

    // 画像がある場合
    if ($form['image'] !== '') {
      $stmt = $db->prepare('insert into images(path) VALUES(:path)');
      if (!$stmt) {
        die($db->error);
      }
      $stmt->bindValue(':path', $form['image'], PDO::PARAM_STR);
      $success = $stmt->execute();
      if (!$success) {
        die($db->error);
      }
      $get_image_id = $db->prepare("select id from images where path = '" . $form['image'] . "'");
      $get_image_id->execute();
      $image_id_str = $get_image_id->fetch(PDO::FETCH_COLUMN);
      $image_id = intval($image_id_str);
      unset($stmt);
    } else {
      // 画像を指定しない場合は以前の写真を使用
      $image_id = $student_info['image_id'];
    }

    // 情報をテーブルに保存
    $stmt = $db->prepare("UPDATE students
                           SET first_name = :first_name,
                               last_name = :last_name,
                               sex = :sex,
                               email = :email,
                               is_active = :is_active,
                               updated_at = :updated_at
                        WHERE id = :student_id");
    if (!$stmt) {
      die($db->error);
    }
    $stmt->bindValue(':first_name', $form['first_name'], PDO::PARAM_STR);
    $stmt->bindValue(':last_name', $form['last_name'], PDO::PARAM_STR);
    $stmt->bindValue(':sex', $form['sex'], PDO::PARAM_STR);
    $stmt->bindValue(':email', $form['email'], PDO::PARAM_STR);
    $stmt->bindValue(':is_active', $form['is_active'], PDO::PARAM_INT);
    $stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
    $stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
    $success = $stmt->execute();
    if (!$success) {
      die($db->error);
    }

    // 所属クラスと出席番号の情報をbelongsテーブルに保存
    // 進学した後新しいクラスを登録する場合
    if ($this_year > $this_year_class['year']) {
      $stmt_belongs = $db->prepare("INSERT belongs
                                    INTO belongs(student_id, class_id, student_num)
                                  VALUES (:student_id, :class_id, :student_num)");
      $stmt->bindValue(':student_id', $form['student_id'], PDO::PARAM_INT);
      $stmt->bindValue(':class_id', $form['class_id'], PDO::PARAM_INT);
      $stmt->bindValue(':student_num', $this_year_class['student_num'], PDO::PARAM_INT);
      $success = $stmt_belongs->execute();
      if (!$success) {
        die($db->error);
      }
      // 現在所属のクラスを変更する場合
    } else {
      $stmt_belongs = $db->prepare("UPDATE belongs
                                     SET class_id = :class_id,
                                         student_num = :student_num,
                                         updated_at = :update_at
                                   WHERE id = :belongs_id");
      $stmt_belongs->bindValue(':class_id', $form['class_id'], PDO::PARAM_INT);
      $stmt_belongs->bindValue(':student_num', $form['student_num'], PDO::PARAM_INT);
      $stmt_belongs->bindValue(':update_at', $current_time, PDO::PARAM_STR);
      $stmt_belongs->bindValue(':belongs_id', $this_year_class['belongs_id'], PDO::PARAM_INT);
      $success_belongs = $stmt_belongs->execute();
      if (!$success_belongs) {
        die($db->error);
      }
    }
    
    // セッション内のフォーム内容を破棄してstudent/home.phpへ
    unset($_SESSION['form']);
    header('Location: home.php');
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>生徒登録確認</title>

  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒登録確認</h1>
    </div>

    <div id="content">
      <?php if (isset($_SESSION['teacher_id'])) : ?>
        <div style="text-align: right"><a href="../teacher/home.php">教員ホームへ</a></div>
      <?php endif; ?>
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>
      <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
      <form action="" method="post" enctype="multipart/form-data">
        <dl>
          <dt>姓</dt>
          <?php if (isset($error['last_name']) && $error['first_name'] === 'blank') : ?>
            <p class="error">* 苗字を入力してください</p>
          <?php endif; ?>
          <dd>
            <input type="text" name="last_name" size="35" maxlength="255" value="<?php echo h($student_info['last_name']); ?>" />
          </dd>

          <dt>名</dt>
          <?php if (isset($error['first_name']) && $error['first_name'] === 'blank') : ?>
            <p class="error">* 名前を入力してください</p>
          <?php endif; ?>
          <dd>
            <input type="text" name="first_name" size="35" maxlength="255" value="<?php echo h($student_info['first_name']); ?>" />
          </dd>
          <dt>性別</dt>
          <?php if (isset($_SESSION['teacher_id'])) : ?>
            <?php if (isset($error['sex']) && $error['sex'] === 'blank') : ?>
              <p class="error">* 性別を入力してください</p>
            <?php endif; ?>
            <dd>
              <input type="radio" name="sex" value="男" <?php if($student_info['sex'] == "男") echo 'checked'; ?>>男
              <input type="radio" name="sex" value="女" <?php if($student_info['sex'] == "女") echo 'checked'; ?>>女
            </dd>
          <?php else : ?>
            <dd>
              <?php echo h($student_info['sex']); ?>
            </dd>
          <?php endif; ?>

          <dt>クラス</dt>
          <?php if (isset($error['class_id']) && $error['class_id'] === 'blank') : ?>
            <p class="error">* クラスを入力してください</p>
          <?php endif; ?>
          <dd>
            <select size="1" name="class_id">
              <option value="0">-</option>
              <?php
              foreach ($selectable_classes as $class) {
                if ($class['id'] == $this_year_class['class_id']) {
                  echo "<option value={$class['id']} selected> {$class['grade']} - {$class['class']}</option>";
                } else {
                  echo "<option value={$class['id']}> {$class['grade']} - {$class['class']}</option>";
                }
              }
              ?>
            </select>
          </dd>

          <dt>出席番号</dt>
          <?php if (isset($error['student_num']) && $error['student_num'] === 'blank') : ?>
            <p class="error">* 出席番号を入力してください</p>
          <?php endif; ?>
          <dd>
            <input type="number" min="1" max="40" name="student_num" value="<?php echo h($this_year_class['student_num']); ?>" />
          </dd>

          <dt>メールアドレス</dt>
          <?php if (isset($error['email']) && $error['email'] === 'blank') : ?>
            <p class="error">* メールアドレスを入力してください</p>
          <?php endif; ?>
          <dd>
            <input type="text" name="email" size="35" maxlength="255" value="<?php echo h($student_info['email']); ?>" />

          <dt>パスワード</dt>
          <dd>
            <p>パスワードの変更は<a href="reset_password.php">こちら</a>から。</p>
          </dd>

          <?php if (isset($_SESSION['teacher_id']) || $this_year > $this_year_class['year']) : ?>
            <dt>写真など</dt>
            <dd>
              <input type="file" name="image" size="35" value="" />
              <?php if (isset($error['image']) && $error['image'] === 'type') : ?>
                <p class="error">* 写真などは「.png」または「.jpg」の画像を指定してください</p>
                <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
              <?php endif; ?>
            </dd>
          <?php endif ?>

          <?php if (isset($_SESSION['teacher_id'])) : ?>
            <dt>在籍情報</dt>
            <dd>
              <input type="radio" name="is_active" value=0 <?php if($student_info['is_active'] == 0) echo 'checked'; ?>>除籍
              <input type="radio" name="is_active" value=1 <?php if($student_info['is_active'] == 1) echo 'checked'; ?>>在籍
            </dd>
          <?php endif; ?>
        </dl>
        <div><input type="submit" value="生徒情報を更新" /></div>
    </div>

  </div>
</body>

</html>