<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags-->
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Личный кабинет</title>

        <!-- Bootstrap core CSS-->
        <link href="/storage/admin/bootstrap.min.css" rel="stylesheet">
        <link href="/storage/cabinet/main.css" rel="stylesheet">


        <link href="navbar.css" rel="stylesheet">

        <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
        <!--[if lt IE 9]>-->
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    </head>

    <body>

        <div class="container">

            <!-- Static navba-->
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">%GAME_TITLE%</a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="#">Главная</a></li>
                            <li><a href="#">Форум</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Аккаунт: <b>%CURRENT_PLAYER%</b><span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Личные данные</a></li>     
                                    <li><a href="#">Настройки</a></li>
                                </ul>
                            </li>

                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <!--<li class="active"><a href="./">Default <span class="sr-only">(current)</span></a></li>-->
                        </ul>

                    </div><!--/.nav-collapse -->
                </div><!--/.container-fluid-->
            </nav>
            <!-- Modal -->
            <center>
                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Создание нового персонажа</h4>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-info" role="alert">Выбирайте имя персонажа тщательно, сменить его вы сможете только обратившись в тех.поддержку</div>
                                <form class="form-horizontal" method="post" action="/cabinet/makechar">
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">Имя персонажа</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="name" required class="form-control" id="inputEmail3" placeholder="Введите имя персонажа">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">Пол персонажа</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="gender">
                                                <option>Мужской</option>
                                                <option>Женский</option>
                                            </select>                                    
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-2 control-label">О персонаже (необязательно)</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="about" rows="3"></textarea>                                  
                                        </div>
                                    </div>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                                <input type="submit" class="btn btn-success" value="Создать">
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </center>
            
            <!-- Main component for a primary marketing message or call to action-->
            <div class="jumbotron">
                <h1>Личный кабинет персонажа</h1>
                <h2>Привет, %CURRENT_PLAYER%</h2>
                <p>Начните играть прямо сейчас, или ознакомьтесь со всеми функциями личного кабинета для более комфортной игры!</p>
                <button type="button" class="btn btn-success" data-toggle="modal" data-target=".bs-example-modal-lg"><i class="glyphicon glyphicon-plus-sign"></i> Новый персонаж (у вас %CHARS_COUNT%)</button>
                <table class="table table-striped">%LIST%
                    <thead>
                        <tr>
                            <th>Имя персонажа</th>
                            <th>Монеты</th>
                            <th>Золото(Донат)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <p>
                    <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">Ознакомиться с документацией по игре &raquo;</a>
                </p>
            </div>

        </div> <!-- /container -->
        <footer class="footer">
            <div class="container">
                <p class="text-muted">&copy 2013-%YEAR%, RAPTOR Game Engine<span style="float:right;">Данный шаблон является стоковым и не обязателен для использования</span></p>
            </div>
        </footer>

        <!-- Bootstrap core JavaScript
        ================================================== 
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="/storage/admin/jquery-1.11.0.js"></script>
        <script src="/storage/admin/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>
