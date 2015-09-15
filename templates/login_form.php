<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="../img/favicon-1.ico?v=1" type="image/x-icon">
  <link rel="icon" href="../img/favicon.ico" type="image/x-icon">

  <?php if (isset($title)): ?>
      <title>Edinote: <?= htmlspecialchars($title) ?></title>
  <?php else: ?>
      <title>Edinote</title>
  <?php endif ?>

  <!-- Bootstrap Core CSS -->
  <link href="/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap login page template -->
  <link href="/css/signin.css" rel="stylesheet">

  <!-- Flat UI CSS -->
  <link href="/flat-ui/dist/css/flat-ui.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="/css/main.css" rel="stylesheet">

  <!-- Custom Fonts -->
  <link href="/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>

<div class="container">
<form class="form-signin" action="login.php" method="post">
  <p class="pull-right" href="#"><img alt="Brand" src="../img/logo-b-1.png"></p></br></br>
  <!-- <h3 class="form-signin-heading">Please sign in</h3> -->
  <label for="inputUsername" class="sr-only">Username</label>
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="username" id="inputUsername" class="form-control" name="username" type="text" placeholder="Username" required autofocus>
  <input type="password" id="inputPassword" class="form-control" name="password" type="password" placeholder="Password" required>
  <!-- <div class="checkbox">
    <label>
      <input type="checkbox" value="remember-me"> Remember me
    </label>
  </div> -->
  <button class="btn btn-lg btn-primary btn-block login-btn" type="submit">Log in</button>
</form>
</div>

</body>

</html>
