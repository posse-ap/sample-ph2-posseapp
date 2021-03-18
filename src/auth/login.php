<?php
session_start();
require('../dbconnect.php');

if (!empty($_POST)) {
  $login = $db->prepare('SELECT * FROM users WHERE email=? AND password=?');
  $login->execute(array(
    $_POST['email'],
    sha1($_POST['password'])
  ));
  $user = $login->fetch();

  if ($user) {
    $_SESSION = array();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['time'] = time();

    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
    exit();
  } else {
    $error = 'fail';
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <link rel="stylesheet" href="/css/style.css">
  <title>POSSE | Login</title>
</head>

<body>
  <div class="container">
    <div class="auth-container">
      <h1 class="mb-5">ログイン</h1>
      <form action="/auth/login.php" method="POST">
        <label>
          <p>メールアドレス</p>
          <input type="email" name="email" required value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES); ?>">
        </label>
        <label>
          <p>パスワード</p>
          <input type="password" required name="password">
        </label>
        <?php if ($error === 'fail') : ?>
          <p class="text-danger text-left ml-2 mb-0">入力された値が一致しません。</p>
        <?php endif; ?>
        <input type="submit" value="ログイン" class="btn btn-primary my-4">
        <a href="/auth/register.php" class="d-block small text-secondary">会員登録はこちら</a>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>

</html>