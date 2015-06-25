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
		<link href="/storage/game/ui.css" rel="stylesheet">
		<script src="/storage/static/jquery.js"></script>
		<script src="/storage/static/messager.js"></script>
		<script src="/storage/static/raptor.js"></script>
	    <script type="text/javascript" src="/storage/static/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
        <link rel="stylesheet" href="/storage/static/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
        <script type="text/javascript" src="/storage/static/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>
        <link rel="stylesheet" href="/storage/static/fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
        <script type="text/javascript" src="/storage/static/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
        <script type="text/javascript" src="/storage/static/fancybox/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
        <link rel="stylesheet" href="/storage/static/fancybox/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
        <script type="text/javascript" src="/storage/static/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
        <script type="text/javascript">
		$(document).ready(function() {
			$(".fancybox").fancybox();
		});
        $(".iframe").fancybox({
                openEffect  : 'none',
                closeEffect : 'none',
                afterLoad   : function() {
                }
        });
		document.getElementById('gui').width = window.screen.width;
		document.getElementById('gui').height = window.screen.height;
        </script>

		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper" class="leftmenu">
            <ul class="sidebar-nav">
                <li class="sidebar-brand">
					<li><a href="#char" class="iframe"><img align="middle" class="leftmenu" width="50" height="50" border="2" src="/storage/img/buttons/b_character.png"> Мой персонаж</a></li>
					<li><a href="#money" class="iframe"><img class="leftmenu" width="50" height="50" border="2" src="/storage/img/buttons/b_money.png"> Деньги</a></li>
					<li><a href="#paidservices" class="iframe"><img class="leftmenu" width="50" height="50" border="2" src="/storage/img/buttons/b_money.png"> Платные услуги</a></li>
					<li><a href="#inv" class="iframe"><img align="middle" class="leftmenu" width="50" height="50" border="2" src="/storage/img/buttons/b_inventory.png"> Инвентарь</a></li>
					<li><a href="#settings" class="iframe"><img align="middle" class="leftmenu" width="50" height="50" border="2" src="/storage/img/buttons/b_settings.png"> Настройки</a></li>
				</li>
				<hr>
				<div id="online-box">
				</div>
				<hr>
				<div class="down-box">
					%CHATBOX%
				</div>
			</ul>
        </div>
        <div id="page-content-wrapper">
            %GUI%
        </div>
    </div>

    <script src="/storage/admin/bootstrap.min.js"></script>
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>
	
	<div id="char" style="display:none;width:100%;">
            <iframe src="/player" width="1000" height="1000" scrolling="no">Ваш браузер не поддерживает IFrame</iframe>
        </div>
        <div id="money" style="display:none;width:100%;">
            <iframe src="/money" width="1000" height="1000" scrolling="no">Ваш браузер не поддерживает IFrame</iframe>
        </div>
        <div id="inv" style="display:none;width:100%;">
            <iframe src="/inv" width="1000" height="1000" scrolling="no">Ваш браузер не поддерживает IFrame</iframe>
        </div>
        <div id="settings" style="display:none;width:100%;">
            <iframe src="/settings" width="1000" height="1000" scrolling="no">Ваш браузер не поддерживает IFrame</iframe>
        </div>
		<div id="paidservices" style="display:none;width:100%;">
            <iframe src="/paidservices" width="1000" height="1000" scrolling="no">Ваш браузер не поддерживает IFrame</iframe>
        </div>
</body>

</html>
