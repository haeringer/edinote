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

  <!-- Flat UI CSS -->
  <link href="/flat-ui/dist/css/flat-ui.min.css" rel="stylesheet">

  <!-- Custom scrollbar CSS -->
  <link href='/css/perfect-scrollbar.min.css' rel='stylesheet'>

  <!-- Custom CSS -->
  <link href="/css/main.css" rel="stylesheet">

</head>

<body>

<!-- SaveModal -->
<div class="modal" id="SaveModal" tabindex="-1" role="dialog" aria-labelledby="SaveModalLabel">
  <form name="saveAs" action="">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="SaveModalLabel">Save new file as...</h4>
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
  <nav class="navbar navbar-fixed-top" role="navigation">

    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>

    <div class="navbar-header">

      <div class="btn-toolbar" role="toolbar">
        <div class="btn-group" role="group">
          <button type="button" id="new" class="btn btn-default navbar-btn" data-toggle="tooltip" data-placement="bottom" title="New">
            <span class="fui-plus" aria-hidden="true"></span>
          </button>
          <button type="button" id="delete" class="btn btn-default navbar-btn" data-toggle="tooltip" data-placement="bottom" title="Delete">
            <span class="fui-cross" aria-hidden="true"></span>
          </button>
          <button type="button" id="save" class="btn btn-default navbar-btn disabled" data-toggle="tooltip" data-placement="bottom" title="Save">
            <span class="fui-check" aria-hidden="true"></span>
          </button>
        </div>
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-default navbar-btn" data-toggle="tooltip" data-placement="bottom" title="View mode">
            <span class="fui-eye" aria-hidden="true"></span>
          </button>
        </div>
      </div>

    </div>

    <a class="navbar-brand brand" href="#"><img alt="Brand" src="../img/Edinote.png"></a>
    <!-- /.navbar-header -->

    <!-- Non-collapsing right-side stuff (dropdown) -->
    <ul class="nav navbar-top-links navbar-right">

      <li class="dropdown pull-right">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <i class="glyphicon glyphicon-user"></i> <i class="glyphicon glyphicon-chevron-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
          <li><a href="#"><i class="glyphicon glyphicon-user"></i> User Profile</a>
          </li>
          <li><a href="#"><i class="fui-gear"></i> Settings</a>
          </li>
          <li class="divider"></li>
          <li><a href="logout.php"><i class="fui-export"></i> Logout</a>
          </li>
        </ul>
        <!-- /.dropdown-user -->
      </li>
      <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <!-- collapsing navbar content (sidebar) -->
    <div class=".navbar-default sidebar" role="navigation">
      <div class="sidebar-nav navbar-collapse" id="sidebar-content">

        <ul class="nav" id="side-menu">
          <li class="sidebar-search">
            <input type="text" class="search form-control" placeholder="Search...">
          </li>
        </ul>
        <ul class="list pre-scrollable" id="file-list">

          <div id="new-file"></div>

          <?php
            foreach($files as $file) {
                /* echo '<div id="f_' . $file . '"><li class="list-group-item
                  button btn btn-default" type="button">' . $file . '</li></div>'; */
                echo '<div><button class="list-group-item">' . $file . '</button></div>' . "\n";
            }
          ?>

        </ul>

      </div>
      <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
  </nav>

  <div id="page-wrapper">

    <div id="editor-container"></div>

  </div>
  <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="/js/jquery.min.js"></script>

<!-- Custom scrollbar -->
<script src='/js/perfect-scrollbar.min.js'></script>

<!-- List.js for file list filter -->
<script src='/js/list.min.js'></script>

<!-- lazy loading
<script src="/js/jquery.jscroll.min.js"></script>
<script src="/js/jquery.endless-scroll.js"></script>
<script src="/js/jquery.lazyload.min.js"></script> -->

<!-- Bootstrap Core JavaScript -->
<script src="/js/bootstrap.min.js"></script>

<!-- Ace editor source -->
<script src="/js/ace-builds/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>

<!-- App JavaScript -->
<script src="/js/scripts.js"></script>

</body>

</html>
