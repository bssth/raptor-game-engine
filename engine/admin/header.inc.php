<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">

        <title>Административный интерфейс</title>

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
                    <a class="navbar-brand" href="index">Админ-панель <?= @$GLOBALS['name']; ?></a>
                </div>

                <ul class="nav navbar-right top-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
                        <ul class="dropdown-menu message-dropdown">
                            <?php
                            $reports = Database::Get("reports", array())->limit(10);
                            foreach ($reports as $r) {
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
                                <a href="/admin/reports">Прочитать все...</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
                        <ul class="dropdown-menu alert-dropdown">
                            <?php
                            $errors = Database::GetAll("errors")->limit(10);
                            foreach ($errors as $error) {
                                echo '<li><a href="/admin/errors"><span class="label label-danger">' . substr($error['text'], 0, 40) . '...</span></a></li>';
                            }
                            ?>
                            <li class="divider"></li>
                            <li>
                                <a href="/admin/errors">Показать все ошибки</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?= $char->name ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="/player/<?= $char->name ?>"><i class="fa fa-fw fa-user"></i>Мой профиль</a>
                            </li>
                            <li>
                                <a href="/admin/reports"><i class="fa fa-fw fa-envelope"></i> Репорты</a>
                            </li>
                            <li>
                                <a href="/admin/config"><i class="fa fa-fw fa-gear"></i> Настройки</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="/cabinet?logout=1"><i class="fa fa-fw fa-power-off"></i>Выход</a>
                            </li>
                        </ul>
                    </li>
                </ul>

                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav side-nav">
                        <li>
                            <a href="/admin/index"><i class="fa fa-fw fa-dashboard"></i> Главная</a>
                        </li>
                        <li>   
                            <a href="javascript:;" data-toggle="collapse" data-target="#char"><i class="fa fa-fw fa-user"></i> Персонажи <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="char" class="collapse">
                                <li><a href="/admin/find">Поиск персонажа</a></li>
								<li><a href="/admin/online">Персонажи онлайн</a></li>
                                <li><a href="/admin/params">Параметры персонажа</a></li>
                                <li><a href="/admin/perms">Редактор прав доступа</a></li>
                                <li><a href="/admin/auth">Общие настройки</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="/admin/design"><i class="fa fa-fw fa-desktop"></i> Шаблоны</a>
                        </li>
						<li>
                            <a href="/admin/news"><i class="fa fa-fw fa-calendar"></i> Новости</a>
                        </li>
                        <li>
                            <a href="/admin/scripts"><i class="fa fa-fw fa-edit"></i> Скрипты</a>
                        </li>
                        <li>
                            <a href="/admin/mail"><i class="fa fa-envelope"></i> Рассылки</a>
                        </li>
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#graphic"><i class="fa fa-fw fa-folder"></i> Графика <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="graphic" class="collapse">
								<li><a href="/admin/grsettings">Настройки графики</a></li>
                                <li><a href="/admin/graphic">Список папок с графикой</a></li>
                                <li><a href="/admin/graphic?sel=Tilesets">Папка - Тайл-сеты</a></li>
                                <li><a href="/admin/graphic?sel=Autotiles">Папка - Ауто-тайлы</a></li>
                                <li><a href="/admin/graphic?sel=Characters">Папка - Персонажи</a></li>
                                <li><a href="/admin/graphic?sel=Icons">Папка - Иконки</a></li>
                                <li><a href="/admin/graphic?sel=Pictures">Папка - Картинки</a></li>
                                <li><a href="/admin/graphic?sel=Fonts">Папка - Шрифты</a></li>
                                <li><a href="/admin/graphic?sel=Items">Папка - Инвентарь</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#economic"><i class="fa fa-fw fa-money"></i> Экономика <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="economic" class="collapse">
                                <li><a href="/admin/currency">Валюты</a></li>
                                <li><a href="/admin/paidservice">Платные услуги</a></li>
								<li><a href="/admin/payments">Приём платежей</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#locations"><i class="fa fa-fw fa-globe"></i> Локации</a>
                            <ul id="locations" class="collapse">
                                <li><a href="/admin/locations">Локации</a></li>
								<li><a href="/admin/grsettings">Настройки ресурсов</a></li>
                                <li><a href="/admin/mapedit">Редактор карт</a></li>
                                <li><a href="/admin/scredit">Скрипты (javascript)</a></li>
                                <li><a href="javascript:;" data-toggle="collapse" data-target="#graphic">Графика (тайлы, NPC и т.д.)</a></li>
                            </ul>
                        </li>
						<li>
                            <a href="/admin/chat"><i class="fa fa-fw fa-comments"></i> Чат</a>
                        </li>
                        <li>
                            <a href="/admin/config"><i class="fa fa-fw fa-wrench"></i> Настройки игры</a>
                        </li>
						<li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#inv"><i class="fa fa-fw fa-flag-o"></i> Инвентарь <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="inv" class="collapse">
                                <li><a href="/admin/inv">Управление предметами</a></li>
								<li><a href="/admin/inv_params">Параметры предметов</a></li>
								<li><a href="/admin/inv_scripts">Действия над предметами</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#wiki"><i class="fa fa-fw fa-book"></i> Wiki <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="wiki" class="collapse">
                                <li><a href="/admin/wiki_menu">Управление меню</a></li>
                                <li><a href="/admin/wiki_articles">Управление статьями</a></li>
                                <li><a href="/admin/wiki_pages">Управление страницами</a></li>
                                <li><a href="/admin/wiki_settings">Настройки</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#modules"><i class="fa fa-fw fa-magic"></i> Модули <i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="modules" class="collapse">
                                <li><a href="/admin/mods">- Менеджер модулей -</a></li>
                                <?php
                                $mclass = new Modules();
                                $mods = $mclass->getModules();
                                foreach ($mods as $mod) {
                                    echo '<li><a href="/admin/ext_' . $mod . '">' . $mod . '</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#raptor"><i class="fa fa-fw fa-plane"></i> Движок</a>
                            <ul id="raptor" class="collapse">
                                <li><a href="/admin/update">Новости</a></li>
								<li><a href="/admin/register">Каталог</a></li>
								<li><a href=#>Версия: <?=ENGINE_VERSION;?></a></li>
                            </ul>
                        </li>


                    </ul>
                </div>
            </nav>

            <div id="page-wrapper">

                <div class="container-fluid">
