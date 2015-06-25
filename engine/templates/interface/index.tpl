<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>%GAME_TITLE%</title>
    <link href="/storage/admin/bootstrap.min.css" rel="stylesheet">
    <link href="/storage/site/index.css" rel="stylesheet">
	<script type="text/javascript" src="http://vk.com/js/api/share.js?90" charset="windows-1251"></script>
	<script type="text/javascript" src="http://vk.com/js/api/openapi.js?115"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
	<script type="text/javascript" src="%STORAGE_STATIC_URL%/mainpage.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">%GAME_TITLE%</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="/forum">Форум</a>
                    </li>
                    <li>
                        <a href="/wiki">Wiki</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">

        <div class="row">

            <div class="col-lg-8">

                <hr>

                <img class="img-responsive" src="http://placehold.it/900x300" alt="">

                <hr>
				<form class="form-signin" role="form" action="%LOGIN_URL%" method="post">
					<input type="text" name="name" class="form-control" placeholder="Логин" required autofocus>
					<input type="password" name="password" class="form-control" placeholder="Пароль" required>
					<button class="btn btn-lg btn-primary btn-block" type="submit">Вход</button> <a class="btn btn-lg btn-primary btn-block" id="register" data-modal-id="modal-window-1">Регистрация</a> 
				</form>
				<hr>

                %NEWS%

            </div>
            <div class="col-md-4">

                <div class="well">
                    <h4>Поиск по справочнику</h4>
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button">
                                <span class="glyphicon glyphicon-search"></span>
                        </button>
                        </span>
                    </div>
                </div>

                <div class="well">
                    <h4>Галерея</h4>
                    <p><img src="http://placehold.it/150x150" alt=""><img src="http://placehold.it/150x150" alt=""><img src="http://placehold.it/150x150" alt=""><img src="http://placehold.it/150x150" alt=""></p>
                </div>

            </div>

        </div>

        <hr>

        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; %GAME_TITLE% 2014</p>
                </div>
            </div>
        </footer>

    </div>

    <script src="/storage/static/jquery.js"></script>

    <script src="/storage/static/bootstrap.min.js"></script>
	
	<div id="modal-window" class="modal-window" >
		<div id='modal-head' class="modal-head"><h2>Регистрация</h2></div>
		<div id='modal-body' class="modal-body">
		%REGISTER%
		</div>
		<div id='modal-footer' class = "modal-footer">
			<span class='modal-button close-button'>Закрыть</span>
		</div>
	</div>

</body>

</html>
