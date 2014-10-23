<?php 
  include 'lib/ImgManager.class.php';
  $pct = new ImgManager();
  $value = $pct->LoadImage((int)$_GET['id']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Php Img Managers</title>

    <!-- Bootstrap -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/imgTools.js"></script>
    <script>
      $(function() {
        IMGTOOLS.init({
          'container' : $("#imgTools")
        });
      });
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="navbar navbar-inverse navbar-static-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Php Images Manager</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.php">Add New</a></li>
            <li><a href="manager.php">Manager</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container" id="imgTools">
      <div class="jumbotron">
        <h1>Upload Image</h1>
        <br>

        <div class="alert" role="alert">
          <p></p>
          <br>
          <div class="glp">
            <span class="glyphicon glyphicon-repeat glyphicon-bg reload"></span>
          </div>
        </div>

        <form role="form" method="POST" action="" enctype="multipart/form-data">
          <div class="checkbox">
            <label>
              <input type="checkbox" name="status" <?php if($value["status"] == 1): ?>checked<?php endif; ?>> Publish?
            </label>
          </div>

          <div class="input-group">
            <span class="input-group-addon">
              <label>Title:<label>
            </span>
              <input type="hidden" value="<?php echo $value['id']; ?>" name="id" />
              <input type="text" name="title" class="form-control" value="<?php echo $value['title']; ?>" placeholder="Picture Title" required />
            </div>
          <br>

          <div class="input-group">
            <span class="input-group-addon">
              <label>Meta:<label>
            </span>
            <input type="text" name="meta" class="form-control" value="<?php echo $value["meta"]; ?>" placeholder="" />
          </div>
          <br>

          <textarea class="form-control form-control-textarea" name="description" placeholder="Description"><?php echo trim($value['description']); ?></textarea>
          <br>

          <div class="form-group">
            <label for="img">File input</label>
            <input type="file" name="img"  />
            <p class="help-block">Max File Size: 1MB</p>
            <p class="help-block">(png|jpg|jpeg|gif)</p>
          </div>
          <br>
          <div class="preview">
            <input type="hidden" name="pctname" class="pctname" value="<?php echo $value['file_name']; ?>" />
            <img src="gallery/th_<?php echo $value['file_name']; ?>" />
          </div>
          <br>
          <input type="hidden" value="edit" name="submit" />
          <button type="submit"  class="btn btn-primary btn-lg">Submit</button>
        </form>
      </div>

      <!-- loader -->
      <div class="modal fade" id="loader" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <h1>
            <span class="label label-default"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</span>
          </h1>
        </div>
      </div><!-- /loader -->

    </div><!--/.container -->

  </body>
</html>