<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Информация о персонаже %name%">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title>%name%</title>

    <link href="/storage/admin/bootstrap.min.css" rel="stylesheet">
    <link href="/storage/cabinet/main.css" rel="stylesheet">

    <link href="navbar.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">
      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron" style="">
        <h2>Информация о персонаже %name%</h2>
        <p><img style="float:right;" src='%avatar%' border='20'>
            <div style="height:500px;">
				%PARAMS_ALL%
            </div>
        </p>
      </div>

    </div> <!-- /container -->
 <footer class="footer">
      <div class="container">
        <p class="text-muted">&copy 2013-%YEAR%, RAPTOR Game Engine<span style="float:right;">Данный шаблон является стоковым и не обязателен для использования</span></p>
      </div>
    </footer>

    <script src="/storage/admin/jquery-1.11.0.js"></script>
    <script src="/storage/admin/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
