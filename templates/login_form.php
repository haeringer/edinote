
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
