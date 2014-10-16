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
          <a class="navbar-brand" href="#">Php Images Manager</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">
      <div class="jumbotron" id="imgTools">
        <h1>Upload Image</h1>
        <br>
        <form role="form" method="POST" action="" enctype="multipart/form-data">
          <input type="hidden" name="pctname" class="pctname" value="" />
          <input type="text" name="name" class="form-control" value="" placeholder="Picture Name" />
          <br>

          <div class="row">
            <div class="col-lg-4">
              <div class="input-group">
                <span class="input-group-addon">
                  <label>Size:<label>
                </span>
                <input type="text" name="size" class="form-control" value="210 mm X 297 mm" placeholder="210 mm X 297 mm" />
              </div>
            </div>
            <div class="col-lg-4">
              <div class="input-group">
                <span class="input-group-addon">
                  <label>Technique:</label>
                </span>
                <input type="text" name="tech" value="Penna a Sfera" class="form-control" placeholder="Penna a Sfera" />
              </div>
            </div>
            <div class="col-lg-4">
              <div class="input-group">
                <span class="input-group-addon">
                  <label>Material:</label>
                </span>
                <input type="text" name="mat" class="form-control" value="Carta" placeholder="Carta" />
              </div>
            </div>
          </div>
          <br>

          <textarea class="form-control" placeholder="Description"></textarea>
          <br>

          <div class="form-group">
            <label for="exampleInputFile">File input</label>
            <input type="file" name="img" required />
            <p class="help-block">Max File Size: 1MB</p>
          </div>

          <div class="response" style="display:none;">
            <img src="" style="margin:10px 0 20px 0"/>
          </div>
          
          <input type="hidden" value="submit" name="submit" />
          <button type="submit"  class="btn btn-primary btn-lg">Submit</button>
        </form>
      </div>
    </div><!--/.container -->

  </body>
</html>