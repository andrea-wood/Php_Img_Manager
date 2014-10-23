<?php 
  include 'lib/ImgManager.class.php';
  $pct = new ImgManager();
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
            <li><a href="index.php">Add New</a></li>
            <li class="active"><a href="manager.php">Manager</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container" id="imgTools">
      <div class="jumbotron">
        <table class="table table-striped">
          <tbody>
            <?php foreach($pct->ListImage() as $value): ?>         
                <tr <?php if($value['status'] == 0): ?>class="warning"<?php endif; ?> data-id="<?php echo $value["id"]; ?>">
                  <td><img class="th_list pull-left" src="gallery/th_<?php echo $value['file_name']; ?>" alt="" /></td>
                  <td><h3><?php echo $value['title']; ?></h3></td>
                  <td>
                    <div class="dropdown pull-right">
                      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
                        Actions
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="edit.php?id=<?php echo $value['id']; ?>">Edit</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="publish-item">Publish</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="unpublish-item">Unpublish</a></li>
                        <li role="presentation" class="divider"></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" class="remove-item"><button type="button" class="btn btn-danger">Delete</button></a></li>
                      </ul>
                    </div>
                  </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
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