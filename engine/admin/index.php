<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Панель администратора
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> Главная
            </li>
        </ol>
    </div>
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <?php
        if ($GLOBALS['debug'] == true) {
            echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            echo '<i class="fa fa-info-circle"></i> Внимание! Игра находится в режиме отладки (debug)';
            echo '</div>';
        }
		if (!file_exists(CACHE_ROOT . SEPARATOR . "installed.cache")) {
            echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            echo '<i class="fa fa-info-circle"></i> Игра не установлена. Пожалуйста, проведите данную процедуру';
            echo '</div>';
        }
        ?>

    </div>
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?= Database::GetAll("chat")->count(); ?></div>
                        <div>Сообщений в чате</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tasks fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?= count($GLOBALS['modules']); ?></div>
                        <div>Модулей загружено</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?= Database::GetAll("payments")->count(); ?></div>
                        <div>Платежей</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-exclamation-triangle fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?= filesize(LOGS_ROOT . SEPARATOR . "errors.log"); ?></div>
                        <div>вес лога ошибок</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i> Последние авторизованные</h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php
                    $u = Database::GetAll("players")->limit(10)->sort(array("last_date" => -1));
                    foreach ($u as $array) {
                        echo '<a href="/admin/find?name='. $array['login'] .'" class="list-group-item"><span class="badge">' . $array['last_ip'] . '</span><i class="fa fa-fw fa-user"></i> ' . $array['login'] . '</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Последние платежи</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Аккаунт</th>
                                <th>Дата платежа</th>
                                <th>Оплачено</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $u = Database::GetAll("payments")->limit(5)->sort(array("dateCreate" => -1));
                            foreach ($u as $array) {
                                echo '<tr><td>' . $array['unitpayId'] . '</td><td>' . $array['account'] . '</td><td>' . $array['dateCreate'] . '</td><td>' . $array['sum'] . '</td>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->