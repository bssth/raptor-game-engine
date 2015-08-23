<h2>Менеджер модулей</h2>
<h5>Ниже доступен список активных и неактивных модулей</h5>
<br>
<hr>

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <td>Модуль</td>
                <td>Статус</td>
                <td>Включить</td>
                <td>Отключить</td>
            </tr>
        </thead>
        <tbody>

            <?php
            $class = new Modules();

            if (isset($_GET['enable'])) 
			{
                $class->enable($_GET['enable']);
                $class->save();
                echo "<div class='alert alert-success'>Модуль включён</div>";
            }
            if (isset($_GET['disable'])) 
			{
                $class->disable($_GET['disable']);
                $class->save();
                echo "<div class='alert alert-success'>Модуль отключён</div>";
            }

            $mods = $class->getModules();

            $skip = array('.', '..', '.htaccess', '.conf');
            $files = scandir(MODS_ROOT);
            foreach ($files as $file) 
			{
                if (!in_array($file, $skip)) 
				{
                    $status = in_array($file, $mods) ? "Включён" : "Отключён";
                    echo "<tr><td> <b><font size=3>" . $file . "</font></b> </td><td> <b>" . $status . "</b> </td><td> [<a href='?enable=" . $file . "'>Включить</a>] </td><td> [<a href='?disable=" . $file . "'>Отключить</a>] </td></tr>";
                }
            }
            ?>

        </tbody>
    </table>
</div>