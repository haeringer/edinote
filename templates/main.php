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
      <title><?= htmlspecialchars($title) ?></title>
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

  <!-- PACE loading indicator CSS -->
  <link href="/css/pace.css" rel="stylesheet">

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
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary" id="submit-fn" >Save</button>
      </div>
    </div>
  </div>
</form>
</div>
<!-- /.SaveModal -->

<!-- TagModal -->
<div class="modal" id="TagModal" tabindex="-1" role="dialog" aria-labelledby="TagModalLabel">
  <form name="tagFile" action="">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="TagModalLabel">Add a tag</h4>
      </div>
      <div class="modal-body">
        <div class="input-group input-group-lg col-sm-12">
          <input type="text" name="tag" class="form-control" id="save-tag">
          <label class="error" for="tag" id="tag_empty"><br>Please enter a tag!</label>
          <label class="error" for="tag" id="tags_full"><br>Sorry, you can assign only up to three tags per file!</label>
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary" id="submit-tag" >Ok</button>
      </div>
    </div>
  </div>
</form>
</div>
<!-- /.TagModal -->

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

    <a class="navbar-brand brand" href="#"><img alt="Brand" src="../img/logo-b-1.png"></a>
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
    <div class="sidebar" role="navigation">
      <div class="sidebar-nav navbar-collapse" id="sidebar-content">

        <ul class="nav" id="side-menu">
          <li class="sidebar-search">
            <input type="text" class="search form-control" placeholder="Search...">
          </li>
        </ul>
        <ul class="list pre-scrollable" id="file-list">

          <div id="new-file"></div>

          <?php
            for ($i = 0; $i < sizeof($files); $i++) {
                ?>
                <li class="list-group-item" id="<?=$files[$i]["fileid"]?>">
                  <div class="lgi-name"><?=substr($files[$i]["file"],0,29)?></div>
                    <div class="tags">
                      <div class="tag" id="tag1_<?=$files[$i]["fileid"]?>"><?=$files[$i]["tag1"]?></div>
                      <div class="tag" id="tag2_<?=$files[$i]["fileid"]?>"><?=$files[$i]["tag2"]?></div>
                      <div class="tag" id="tag3_<?=$files[$i]["fileid"]?>"><?=$files[$i]["tag3"]?></div>
                      <div id="tg_<?=$files[$i]["fileid"]?>"></div>
                    </div>
                </li>
                <?php
            }
          ?>

        </ul>

        <div class="btn-toolbar en-bottom" role="toolbar">
          <div class="btn-group btn-group-xs" role="group">
            <button type="button" id="tag-add" class="btn btn-bottom bottom-disabled" data-toggle="tooltip" data-placement="right" title="Add Tag">
              <span class="fui-plus-circle" aria-hidden="true"></span>
            </button>
            <button type="button" id="tag-rm" class="btn btn-bottom bottom-disabled" data-toggle="tooltip" data-placement="right" title="Remove Tag">
              <span class="fui-cross-circle" aria-hidden="true"></span>
            </button>
            <button type="button" id="rename" class="btn btn-bottom bottom-disabled" data-toggle="tooltip" data-placement="right" title="Rename File">
              <span class="fui-new" aria-hidden="true"></span>
            </button>
          </div>
        </div>


      </div>
      <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
  </nav>

  <div id="page-wrapper">

    <div id="editor-container"></div>
    <div id="md-container"></div>

  </div>
  <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="/js/jquery.min.js"></script>

<!-- PACE loading indicator -->
<script src="/js/pace.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/js/bootstrap.min.js"></script>

<!-- marked markdown parser/compiler -->
<script src='/js/marked.min.js'></script>

<!-- Custom scrollbar -->
<script src='/js/perfect-scrollbar.min.js'></script>

<!-- List.js for file list filter -->
<script src='/js/list.min.js'></script>

<!-- lazy loading
<script src="/js/jquery.jscroll.min.js"></script>
<script src="/js/jquery.endless-scroll.js"></script>
<script src="/js/jquery.lazyload.min.js"></script> -->

<!-- Ace editor source -->
<script src="/js/ace-builds/src-min-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>

<!-- App JavaScript -->
<script src="/js/scripts.js"></script>

</body>

</html>
