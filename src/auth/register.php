<?php
session_start();
require('../dbconnect.php');

if (!empty($_POST)) {
  // メールアドレス重複チェック
  $user = $db->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email=?');
  $user->execute(array($_POST['email']));
  $record = $user->fetch();
  if ($record['cnt'] > 0) {
    $error = 'duplicate';
  }

  if (empty($error)) {
    $generation = intval($_POST['generation']);
    $stmt = $db->prepare('INSERT INTO users SET family_name=?, first_name=?, family_name_hira=?, first_name_hira=?, email=?, password=?, generation=?');
    $stmt->execute(array(
      $_POST['family_name'],
      $_POST['first_name'],
      $_POST['family_name_hira'],
      $_POST['first_name_hira'],
      $_POST['email'],
      sha1($_POST['password']),
      $generation,
    ));
    $login = $db->prepare('SELECT * FROM users WHERE email=? AND password=?');
    $login->execute(array(
      $_POST['email'],
      sha1($_POST['password'])
    ));
    $newUser = $login->fetch();

    $_SESSION = array();
    $_SESSION['user_id'] = $newUser['id'];
    $_SESSION['time'] = time();

    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv=Content-Type content="text/plain; charset=UTF-8">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="/css/style.css">
  <title>POSSE | Register</title>
</head>

<body>
  <div class="container">
    <div class="auth-container">
      <h1 class="mb-5">会員登録</h1>
      <form action="/auth/register.php" method="POST">
        <div class="d-flex">
          <label>
            <p>苗字</p>
            <input type="text" name="family_name" required value="<?php echo htmlspecialchars($_POST['family_name'], ENT_QUOTES); ?>">
          </label>
          <label>
            <p>名前</p>
            <input type="text" name="first_name" required value="<?php echo htmlspecialchars($_POST['first_name'], ENT_QUOTES); ?>">
          </label>
        </div>
        <div class="d-flex">
          <label>
            <p>苗字（ひらがな）</p>
            <input type="text" name="family_name_hira" required value="<?php echo htmlspecialchars($_POST['family_name_hira'], ENT_QUOTES); ?>">
          </label>
          <label>
            <p>名前（ひらがな）</p>
            <input type="text" name="first_name_hira" required value="<?php echo htmlspecialchars($_POST['first_name_hira'], ENT_QUOTES); ?>">
          </label>
        </div>
        <label>
          <p>メールアドレス</p>
          <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>">
          <?php if ($error === 'duplicate') : ?>
            <p class="text-danger ml-2">※このメールアドレスは既に使用されています</p>
          <?php endif; ?>
        </label>
        <label>
          <p>パスワード</p>
          <input type="password" name="password" minlength="6" required>
        </label>
        <label>
          <p>期生</p>
          <input type="number" name="generation" required value="<?php echo htmlspecialchars($_POST['generation'], ENT_QUOTES); ?>">
        </label>
        <input type="submit" value="登録する" class="btn btn-primary my-4">
        <a href="/auth/login.php" class="d-block small text-secondary">アカウントをお持ちの方</a>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>