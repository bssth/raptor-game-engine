<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            <?=Raptor::get_string('admin')?>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> <?=Raptor::get_string('index')?>
            </li>
        </ol>
    </div>
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <?php
        if ($GLOBALS['debug'] == true) 
		{
            echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            echo '<i class="fa fa-info-circle"></i> Warning! Game is in debug mode';
            echo '</div>';
        }
		if (!file_exists(CACHE_ROOT . SEPARATOR . "installed.cache")) 
		{
            echo '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            echo '<i class="fa fa-info-circle"></i> Game isn\'t installed';
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
                        <div><?=Raptor::get_string('chat')?></div>
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
                        <div><?=Raptor::get_string('modules')?></div>
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
                        <div><?=Raptor::get_string('payments')?></div>
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
                        <div><?=Raptor::get_string('payments')?></div>
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
                <h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i> <?=Raptor::get_string('last_auth')?></h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <?php
                    $u = Database::GetAll("players")->limit(10)->sort(array("last_date" => -1));
                    foreach ($u as $array) 
					{
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
                <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> <?=Raptor::get_string('last_pays')?></h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Account</th>
                                <th>Date</th>
                                <th>Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $u = Database::GetAll("payments")->limit(5)->sort(array("dateCreate" => -1));
                            foreach ($u as $array) 
							{
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