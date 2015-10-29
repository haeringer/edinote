<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Edinote">
  <meta name="author" content="Ben Haeringer">
  <link rel="shortcut icon" href="../img/favicon-1.ico?v=1" type="image/x-icon">
  <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
  <title>&#60;Edinote&#62; Log in</title>

  <!-- Bootstrap Core CSS -->
  <link href="/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap sign-in page template -->
  <link href="/css/bootstrap-login.css" rel="stylesheet">

  <!-- Flat UI CSS -->
  <link href="/flat-ui/dist/css/flat-ui.min.css" rel="stylesheet">

  <!-- Edinote CSS -->
  <link href="/css/edinote-main.css" rel="stylesheet">

</head>

<body>

<div class="container">
<form class="form-signin" action="./" method="post">
  <p class="pull-right"><img alt="&#60;Edinote&#62;" src="../img/logo-b-1.png"></p><br><br>
  <!-- <h3 class="form-signin-heading">Please sign in</h3> -->
  <label for="inputUsername" class="sr-only">Username</label>
  <label for="inputPassword" class="sr-only">Password</label>
  <input id="inputUsername" class="form-control" name="username" type="text" placeholder="Username" required autofocus>
  <input id="inputPassword" class="form-control" name="password" type="password" placeholder="Password" required>
  <!-- <div class="checkbox">
    <label>
      <input type="checkbox" value="remember-me"> Remember me
    </label>
  </div> -->
  <button id="submit-login" class="btn btn-lg btn-primary btn-block confirm-btn" type="button">Log in</button>
  <br>
  <div class="alert alert-danger invalid-login en-hide">Invalid username/password!</div>
  <div class="alert alert-danger empty-username en-hide">Please enter a username!</div>
  <div class="alert alert-danger empty-password en-hide">Please enter a password!</div>
</form>
</div>

<!-- jQuery -->
<script src="/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/js/bootstrap.min.js"></script>

<!-- Edinote login page js -->
<script src="/js/edinote-login.js"></script>

</body>

</html>
