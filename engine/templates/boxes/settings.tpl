<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title>Настройки</title>

    <link href="/storage/admin/bootstrap.min.css" rel="stylesheet">
    <link href="/storage/cabinet/main.css" rel="stylesheet">

    <link href="navbar.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<script>
		function sendReport() {
			$.post('/api?a=makereport', {'text':document.getElementById('report-text').value}, function(data) { console.log("Report sent"); alert("Репорт отправлен"); }, "text");
		}
		function changeEmail() {
			$.get('/api', {'a':'changeemail','new':document.getElementById('new-email').value}, function(data) { console.log("E-Mail changed"); alert("E-MAIL изменён"); }, "text");
		}
		function changePassword() {
			$.get('/api', {'a':'changepass','new':document.getElementById('new-password').value}, function(data) { console.log("Password changed"); alert("Пароль изменён"); }, "text");
		}
	</script>
  </head>

  <body>

    <div class="container">
      <div class="jumbotron" style="">
	  <div class="row">
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Сменить адрес электронной почты</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group input-group">
									<span class="input-group-addon">@</span>
									<input id="new-email" class="form-control" placeholder="" type="text">
								</div>
								<button onclick="changeEmail();" type="submit" class="btn btn-default">Сменить E-MAIL</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Сменить пароль</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group input-group">
									<input id="new-password" class="form-control" placeholder="" type="password">
								</div>
								<button onclick="changePassword();" type="submit" class="btn btn-default">Сменить пароль</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Помощь</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
									<label>Задать вопрос тех. поддержке</label>
									<textarea id="report-text" class="form-control" rows="3"></textarea>
									<button onclick="sendReport();" type="submit" class="btn btn-default">Отправить</button>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
      </div>

    </div> <!-- /container -->
 <footer class="footer">
      <div class="container">
        <p class="text-muted">&copy 2013-%YEAR%, RAPTOR Game Engine</p>
      </div>
    </footer>

    <script src="/storage/admin/jquery-1.11.0.js"></script>
    <script src="/storage/admin/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
