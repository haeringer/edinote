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

<!-- SaveModal -->
<div class="modal" id="SaveModal" tabindex="-1" role="dialog" aria-labelledby="SaveModalLabel">
  <form name="saveAs" action="">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="SaveModalLabel">Save file as...</h4>
      </div>
      <div class="modal-body">
        <div class="input-group input-group-lg col-sm-12">
          <input type="text" name="filename" class="form-control" id="save-as" placeholder="Example.md">
          <label class="error" for="filename" id="filename_empty"><br>Please enter a file name!</label>
          <label class="error" for="filename" id="filename_exists"><br>File name already exists!</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submit" >Save</button>
      </div>
    </div>
  </div>
</form>
</div>
<!-- /.SaveModal -->

<div id="wrapper">

  <!-- Navigation -->
  <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">

      <a class="navbar-brand brand" href="#"><img alt="Brand" src="../img/Edinote.png"></a>

      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <div class="btn-toolbar" role="toolbar">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-default navbar-btn">New</button>
          <button type="button" id="save" class="btn btn-default navbar-btn disabled">Save</button>
          <button type="button" id="delete" class="btn btn-default navbar-btn">Delete</button>
        </div>
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-default navbar-btn">Mode</button>
        </div>
      </div>

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

            <div id="new-file"></div>

            <?php
              foreach($files as $file) {
                  echo '<div><li class="list-group-item button btn btn-default"
                    type="button">' . $file . '</li></div>';
              }
            ?>

          </ul>
        </ul>
      </div>
      <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
  </nav>

  <div id="page-wrapper">

    <div id="editor-container"><div id="ediContent"></div></div>

  </div>
  <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/js/bootstrap.min.js"></script>

<!-- Ace editor source -->
<script src="/js/ace-builds/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>

<!-- Custom Theme JavaScript -->
<script src="/js/scripts.js"></script>

</body>

</html>
