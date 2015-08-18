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

  <!-- Custom CSS -->
  <link href="/css/styles.css" rel="stylesheet">

  <!-- Custom Fonts -->
  <link href="/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>

<div id="wrapper">

  <!-- Navigation -->
  <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.html">Edinote</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">

      <li class="dropdown pull-right">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
          <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
          </li>
          <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
          </li>
          <li class="divider"></li>
          <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
          </li>
        </ul>
        <!-- /.dropdown-user -->
      </li>
      <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
      <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
          <li class="sidebar-search">
            <div class="input-group custom-search-form">
              <input type="text" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
              <button class="btn btn-default" type="button">
                <i class="fa fa-search"></i>
              </button>
            </span>
            </div>
            <!-- /input-group -->
          </li>
          <ul class="list-group">

            <?php foreach ($files as $file): ?>

              <li class="list-group-item"><?= $file ?></li>

            <?php endforeach ?>

          </ul>
        </ul>
      </div>
      <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
  </nav>

  <div id="page-wrapper">

    <div id="editor-container"><?php echo htmlentities(file_get_contents($usrdir."/markdown.md")); ?></div>

  </div>
  <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/js/bootstrap.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="/js/sb-admin-2.js"></script>

<script src="/js/ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var editor = ace.edit("editor-container");
    editor.setTheme("ace/theme/tomorrow");
    editor.getSession().setMode("ace/mode/markdown");
</script>

</body>

</html>
