<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">

        <title><?=Raptor::get_string('admin')?></title>

        <link href="/storage/admin/bootstrap.min.css" rel="stylesheet">
        <link href="/storage/admin/sb-admin.css" rel="stylesheet">
        <link href="/storage/admin/font-awesome.min.css" rel="stylesheet" type="text/css">
		<script src="/storage/static/jquery.js"></script>

    </head>

    <body>

        <div id="wrapper">

            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only"> </span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index"><?=Raptor::get_string('admin')?> <?= @$GLOBALS['name']; ?></a>
                </div>

                <ul class="nav navbar-right top-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
                        <ul class="dropdown-menu message-dropdown">
                            <?php
                            $reports = Database::Get("reports", array())->limit(10);
                            foreach ($reports as $r) 
							{
                                echo '<li class="message-preview">
									<a href="#">
										<div class="media">
											<span class="pull-left">
												<img class="media-object" src="http://placehold.it/50x50" alt="">
											</span>
											<div class="media-body">
												<h5 class="media-heading"><strong>' . $r['author'] . '</strong>
												</h5>
												<p class="small text-muted"><i class="fa fa-clock-o"></i> ' . $r['date'] . '</p>
												<p>' . strip_tags($r['message']) . '</p>
											</div>
										</div>
									</a>
								</li>';
                            }
                            ?>
                            <li class="message-footer">
                                <a href="/admin/reports">All...</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                        <ul class="dropdown-menu alert-dropdown">
                            <li>
                                <a href="/admin/errors">more</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?= char()->name ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="/player/<?= char()->name ?>"><i class="fa fa-fw fa-user"></i>Profile</a>
                            </li>
                            <li>
                                <a href="/admin/reports"><i class="fa fa-fw fa-envelope"></i> Reports</a>
                            </li>
                            <li>
                                <a href="/admin/config"><i class="fa fa-fw fa-gear"></i> Settings</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="/cabinet?logout=1"><i class="fa fa-fw fa-power-off"></i>Exit</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav side-nav">
                        <li>
                            <a href="/admin/index"><i class="fa fa-fw fa-dashboard"></i> <?=Raptor::get_string('index')?></a>
                        </li>
						<?=($GLOBALS['debug']==true)?'<li><a href="/admin/debug"><i class="fa fa-fw fa-lock"></i> '.Raptor::get_string('debug').'</a></li>':''?>
                        <li>   
                            <a href="javascript:;" data-toggle="collapse" data-target="#char"><i class="fa fa-fw fa-user"></i> <?=Raptor::get_string('chars')?> <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="char" class="collapse">
                                <li><a href="/admin/find"><?=Raptor::get_string('find')?></a></li>
								<li><a href="/admin/online"><?=Raptor::get_string('online')?></a></li>
                                <li><a href="/admin/params"><?=Raptor::get_string('params')?></a></li>
								<li><a href="/admin/charact"><?=Raptor::get_string('char_acts')?></a></li>
                                <li><a href="/admin/perms"><?=Raptor::get_string('perms')?></a></li>
                                <li><a href="/admin/auth"><?=Raptor::get_string('settings')?></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="/admin/design"><i class="fa fa-fw fa-desktop"></i> <?=Raptor::get_string('templates')?></a>
                        </li>
						<li>
                            <a href="/admin/news"><i class="fa fa-fw fa-calendar"></i> <?=Raptor::get_string('news')?></a>
                        </li>
                        <li>
                            <a href="/admin/scripts"><i class="fa fa-fw fa-edit"></i> <?=Raptor::get_string('scripts')?></a>
                        </li>
                        <li>
                            <a href="/admin/mail"><i class="fa fa-envelope"></i> <?=Raptor::get_string('massives')?></a>
                        </li>
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#graphic"><i class="fa fa-fw fa-folder"></i> <?=Raptor::get_string('graphics')?> <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="graphic" class="collapse">
								<li><a href="/admin/grsettings"><?=Raptor::get_string('settings')?></a></li>
								<li><a href="/admin/graphic"><?=Raptor::get_string('all')?></a></li>
								<?php
									foreach(scandir(STATIC_ROOT . SEPARATOR . "Graphics") as $sub)
									{
										echo '<li><a href="/admin/graphic?sel='. $sub .'">'. ucfirst($sub) .'</a></li>';
									}
								?>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#economic"><i class="fa fa-fw fa-money"></i> <?=Raptor::get_string('economic')?> <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="economic" class="collapse">
                                <li><a href="/admin/currency"><?=Raptor::get_string('currencies')?></a></li>
                                <li><a href="/admin/paidservice"><?=Raptor::get_string('paidservices')?></a></li>
								<li><a href="/admin/payments"><?=Raptor::get_string('payments')?></a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#locations"><i class="fa fa-fw fa-globe"></i> <?=Raptor::get_string('locations')?></a>
                            <ul id="locations" class="collapse">
                                <li><a href="/admin/locations"><?=Raptor::get_string('locations')?></a></li>
								<li><a href="/admin/grsettings"><?=Raptor::get_string('resources')?></a></li>
                                <li><a href="/admin/mapedit"><?=Raptor::get_string('mapedit')?></a></li>
								<li><a href="/admin/loctypes"><?=Raptor::get_string('types')?></a></li>
                                <li><a href="/admin/scredit">JavaScript</a></li>
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#graphic"><?=Raptor::get_string('graphics')?></a></li>
                            </ul>
                        </li>
						<li>
                            <a href="/admin/chat"><i class="fa fa-fw fa-comments"></i> <?=Raptor::get_string('chat')?></a>
                        </li>
                        <li>
                            <a href="/admin/config"><i class="fa fa-fw fa-wrench"></i> <?=Raptor::get_string('settings')?></a>
                        </li>
						<li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#inv"><i class="fa fa-fw fa-flag-o"></i> <?=Raptor::get_string('inventory')?> <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="inv" class="collapse">
                                <li><a href="/admin/inv"><?=Raptor::get_string('items')?></a></li>
								<li><a href="/admin/inv_params"><?=Raptor::get_string('params')?></a></li>
								<li><a href="/admin/inv_scripts"><?=Raptor::get_string('inv_acts')?></a></li>
                            </ul>
                        </li>
                        <!--<li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#wiki"><i class="fa fa-fw fa-book"></i> Wiki <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="wiki" class="collapse">
                                <li><a href="/admin/wiki_menu">Управление меню</a></li>
                                <li><a href="/admin/wiki_articles">Управление статьями</a></li>
                                <li><a href="/admin/wiki_pages">Управление страницами</a></li>
                                <li><a href="/admin/wiki_settings">Настройки</a></li>
                            </ul>
                        </li>-->
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#modules"><i class="fa fa-fw fa-magic"></i> <?=Raptor::get_string('modules')?> <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="modules" class="collapse">
                                <li><a href="/admin/mods">- <?=Raptor::get_string('modules')?> -</a></li>
                                <?php
									$mclass = new Modules();
									$mods = $mclass->getModules();
									foreach ($mods as $mod) 
									{
										echo '<li><a href="/admin/ext_' . $mod . '">' . $mod . '</a></li>';
									}
                                ?>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#raptor"><i class="fa fa-fw fa-plane"></i> Engine</a>
                            <ul id="raptor" class="collapse">
                                <li><a href="/admin/update">News</a></li>
								<li><a href="/admin/register">Catalogue</a></li>
								<li><a href=#>Version: <?=ENGINE_VERSION;?></a></li>
                            </ul>
                        </li>


                    </ul>
                </div>
            </nav>

            <div id="page-wrapper">

                <div class="container-fluid">
