<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Note taking web application for self-hosting. Comes with tagging & Markdown support; can be used as alternative to Evernote.">
  <meta name="author" content="Ben Haeringer">
  <link rel="shortcut icon" href="../img/favicon-1.ico?v=1" type="image/x-icon">
  <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
  <title>&#60;Edinote&#62;</title>

  <!-- NProgress loading indicator CSS -->
  <link href="/css/nprogress-login.css" rel="stylesheet">

  <!-- Bootstrap Core CSS -->
  <link href="/css/bootstrap.min.css" rel="stylesheet">

  <!-- Flat UI CSS -->
  <link href="/flat-ui/dist/css/flat-ui.min.css" rel="stylesheet">

  <!-- Custom scrollbar CSS -->
  <link href='/css/perfect-scrollbar.min.css' rel='stylesheet'>

  <!-- Github markdown CSS -->
  <link href='/css/markdown.css' rel='stylesheet'>

  <!-- Custom CSS -->
  <link href="/css/edinote-main.css" rel="stylesheet">

</head>

<body>

<!-- SaveModal -->
<div class="modal" id="SaveModal" tabindex="-1" role="dialog" aria-labelledby="SaveModalLabel">
  <form name="saveAs">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="SaveModalLabel">Save file as...</h4>
      </div>
      <div class="modal-body">
        <div class="input-group input-group-lg col-sm-12">
          <input type="text" name="filename" class="form-control" id="save-as" pattern="[A-Za-z]{3}">
        </div>
      <div class="alert alert-info" id="filename_empty">Please enter a file name!</div>
      <div class="alert alert-warning" id="validate-f">
        Use max. 50 of the following characters and symbols for filenames:<br>
        Begin and end with A-Za-z0-9, no symbols other than ' - ', ' _ ', '&nbsp; &nbsp;' or ' . '
      </div>
      <div class="alert alert-danger" id="filename_exists">File name already exists!</div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary confirm-btn" id="submit-fn" >Save</button>
      </div>
    </div>
  </div>
</form>
</div>
<!-- /.SaveModal -->

<!-- TagModal -->
<div class="modal" id="TagModal" tabindex="-1" role="dialog" aria-labelledby="TagModalLabel">
  <form name="tagFile">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="TagModalLabel">Add a tag</h4>
      </div>
      <div class="modal-body">
        <div class="input-group input-group-lg col-sm-12">
          <input type="text" name="tag" class="typeahead form-control" id="save-tag">
        </div>
        <div class="alert alert-info" id="tag_empty">Please enter a tag!</div>
        <div class="alert alert-warning" id="validate-t">
          Use max. 10 of the following characters and symbols for tag names:<br>
          A-Z, a-z, 0-9, ._ !"'$()=?+*'#:-
        </div>
        <div class="alert alert-warning" id="tags_full">Sorry, you can assign only up to three tags per file!</div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary confirm-btn" id="submit-tag" >Ok</button>
      </div>
    </div>
  </div>
</form>
</div>
<!-- /.TagModal -->

<!-- AcntModal -->
<div class="modal" id="AcntModal" tabindex="-1" role="dialog" aria-labelledby="AcntModalLabel">
  <form name="Acnt">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="AcntModalLabel">Settings</h4>
      </div>
      <div class="modal-body">
        <div>
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#user">User Account</a></li>
            <?php if ($admin === 'true'): ?>
              <li><a data-toggle="tab" href="#admin">Admin</a></li>
            <?php endif; ?>
          </ul>
          <div class="tab-content">
            <div id="user" class="tab-pane fade in active">
              <div class="btn-group" data-toggle="buttons">
                <h6>Default extension for new files</h6>
                <label class="btn btn-primary en-radio" id="opt-md">
                  <input type="radio" name="opt-md">.md (Markdown)
                </label>
                <label class="btn btn-primary en-radio" id="opt-txt">
                  <input type="radio" name="opt-txt">.txt (Text)
                </label>
              </div>
              <hr>
              <div class="input-group input-group-lg col-sm-12">
                <h6>Change password</h6>
                <input type="password" name="pw" class="form-control" placeholder="New password" id="save-pw">
                <input type="password" name="pw-confirm" class="form-control" placeholder="Confirmation" id="confirm-pw">
              </div>
              <div class="alert alert-danger" id="pw-confirm-nomatch">Password confirmation does not match!</div>
              <div class="alert alert-danger" id="pw-demo">Password change not allowed in demo.</div>
            </div>
            <?php if ($admin === 'true'): ?>
            <div id="admin" class="tab-pane fade">
              <h6>Add user</h6>
                <div class="input-group input-group-lg col-sm-12">
                  <input autofocus class="form-control" id="ua-name" name="username" placeholder="Username" type="text"/>
                  <input class="form-control" id="ua-pw" name="password" placeholder="Password" type="password"/>
                  <button id="useradd" type="button" class="btn btn-primary confirm-btn pull-right">Create</button>
                  <div class="checkbox">
                    <label class="en-label"><input type="checkbox" id="ua-admin" name="admin-check" value="">Admin</label>
                  </div>
                </div>
              <div class="alert alert-info" id="ua-empty">Please fill out username and password!</div>
              <div class="alert alert-warning" id="validate-u">
                Use max. 30 of the following characters and symbols for usernames:<br>
                Begin and end with A-Za-z0-9; no symbols other than ' . ', ' _ ' or ' - '
              </div>
              <div class="alert alert-danger" id="ua-exists">Username already exists!</div>
              <div class="alert alert-success" id="ua-success">User successfully created.</div>
              <hr>
              <h6>Delete user</h6>
                <fieldset>
                  <div class="form-group">
                    <select id="ud-name" class="form-control" name="users">
                      <option value="">Select...</option>
                      <?php foreach ($users as $user): ?>
                        <option value="<?= $user['username'] ?>"><?= $user["username"] ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <button id="userdel" type="button" class="btn btn-primary confirm-btn pull-right">Delete</button>
                  </div>
                </fieldset>
              <div class="alert alert-info" id="ud-empty">No user selected!</div>
              <div class="alert alert-success" id="ud-success">User successfully deleted.</div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default en-hide" id="close-modal" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary confirm-btn" id="submit-acnt" >Ok</button>
      </div>
    </div>
  </div>
</form>
</div>
<!-- /.AcntModal -->

<div id="wrapper" class="en-hide" >

  <!-- Navigation -->
  <nav class="navbar navbar-fixed-top">

    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>

    <div class="navbar-header">

      <div class="btn-toolbar" role="toolbar">
        <div class="btn-group" role="group">
          <button type="button" id="new" class="btn btn-default navbar-btn" data-toggle="tooltip" data-placement="bottom" title="New (Ctrl+Alt+N)">
            <span class="fui-plus" aria-hidden="true"></span>
          </button>
          <button type="button" id="delete" class="btn btn-default navbar-btn disabled" data-toggle="tooltip" data-placement="bottom" title="Delete (Ctrl+Alt+D)">
            <span class="fui-cross" aria-hidden="true"></span>
          </button>
          <button type="button" id="save" class="btn btn-default navbar-btn disabled" data-toggle="tooltip" data-placement="bottom" title="Save (Ctrl+S)">
            <span class="fui-check" aria-hidden="true"></span>
          </button>
        </div>
        <div class="btn-group" role="group">
          <button type="button" id="mode" class="btn btn-default navbar-btn" data-toggle="tooltip" data-placement="bottom" title="View mode (Ctrl+Alt+M)">
            <span class="fui-eye" aria-hidden="true"></span>
          </button>
        </div>
      </div>

    </div>
    <div id='loading-spinner'><img alt="loading..." src="../img/loading.gif"></div>
    <!--<a class="navbar-brand brand" href="#"><img alt="Edinote" src="../img/logo-b-1.png"></a>-->
    <!-- /.navbar-header -->

    <!-- Non-collapsing right-side stuff (dropdown) -->
    <ul class="nav navbar-top-links navbar-right">

      <li class="dropdown pull-right">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <i class="glyphicon glyphicon-user"></i> <i class="glyphicon glyphicon-chevron-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
          <li><a href="javascript:void(0);" id="btn-settings"><i class="fui-gear"></i> Settings</a></li>
          <li><a href="logout.php"><i class="fui-export"></i> Logout</a></li>
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
            <input type="text" class="search form-control" placeholder="Search..." id="filter">
          </li>
        </ul>
        <div id="ls-top" class="list-width"></div>
        <ul class="list pre-scrollable" id="file-list">

          <li class="hidden" id="list-top"></li>

          <?php for ($i = 0; $i < sizeof($files); $i++) { ?>
            <li class="list-group-item" id="<?=$files[$i]["fileid"]?>">
              <div class="lgi-name"><?=$files[$i]["file"]?></div>
              <div class="tags">
                <div class="tag" id="tag1_<?=$files[$i]["fileid"]?>">
                  <?=$files[$i]["tag1"]?></div>
                <div class="tag" id="tag2_<?=$files[$i]["fileid"]?>">
                  <?=$files[$i]["tag2"]?></div>
                <div class="tag" id="tag3_<?=$files[$i]["fileid"]?>">
                  <?=$files[$i]["tag3"]?></div>
              </div>
            </li>
          <?php } ?>

        </ul>
        <div class="list-shadow list-shadow-bottom list-width"></div>

        <div class="btn-toolbar en-bottom" role="toolbar">
          <div class="btn-group btn-group-s" role="group">
            <button type="button" id="tag-add" class="btn btn-bottom bottom-disabled" data-toggle="tooltip" data-placement="right" title="Add Tag (Ctrl+Alt+T)">
              <span class="glyphicon glyphicon-tag" aria-hidden="true"></span>
            </button>
            <button type="button" id="tag-rm" class="btn btn-bottom bottom-disabled" data-toggle="tooltip" data-placement="right" title="Remove Tag (Ctrl+Alt+E)">
              <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
            </button>
            <button type="button" id="rename" class="btn btn-bottom bottom-disabled" data-toggle="tooltip" data-placement="right" title="Rename File (Ctrl+Alt+R)">
              <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
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
    <div id="md-container" class="markdown-body"></div>

  </div>

</div>
<!-- /#wrapper -->

<!-- requireJS plus Edinote app JavaScript -->
<script data-main="/js/edinote-base.js" src="/js/require.js"></script>

</body>

</html>
