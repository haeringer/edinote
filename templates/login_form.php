
<!-- CS50 original
<form action="login.php" method="post">
    <fieldset>
        <div class="form-group">
            <input autofocus class="form-control" name="username" placeholder="Username" type="text"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="password" placeholder="Password" type="password"/>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-default">Log In</button>
        </div>
    </fieldset>
</form>
<div>
    or <a href="register.php">register</a> for an account
</div>
 -->

<!-- bootstrap template -->
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- <link rel="icon" href="../../favicon.ico"> -->

  <?php if (isset($title)): ?>
      <title>Edinote: <?= htmlspecialchars($title) ?></title>
  <?php else: ?>
      <title>Edinote</title>
  <?php endif ?>

  <!-- Bootstrap Core CSS -->
  <link href="/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap login page template -->
  <link href="/css/signin.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="/css/sb-admin-2.css" rel="stylesheet">

  <!-- Custom Fonts -->
  <link href="/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>

<div class="container">
<form class="form-signin" action="login.php" method="post">
  <h2 class="form-signin-heading">Please sign in</h2>
  <label for="inputUsername" class="sr-only">Username</label>
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="username" id="inputUsername" class="form-control" name="username" type="text" placeholder="Username" required autofocus>
  <input type="password" id="inputPassword" class="form-control" name="password" type="password" placeholder="Password" required>
  <div class="checkbox">
    <label>
      <input type="checkbox" value="remember-me"> Remember me
    </label>
  </div>
  <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>
</div>

</body>

</html>
