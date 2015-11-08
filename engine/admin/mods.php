<h2>Module Manager</h2>
<br>
<hr>

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <td><?=Raptor::get_string('module')?></td>
                <td><?=Raptor::get_string('status')?></td>
                <td><?=Raptor::get_string('enable')?></td>
                <td><?=Raptor::get_string('disable')?></td>
            </tr>
        </thead>
        <tbody>

            <?php
            $class = new Modules();

            if (isset($_GET['enable'])) 
			{
                $class->enable($_GET['enable']);
                $class->save();
                echo "<div class='alert alert-success'>". Raptor::get_string('admin_saved') ."</div>";
            }
            if (isset($_GET['disable'])) 
			{
                $class->disable($_GET['disable']);
                $class->save();
                echo "<div class='alert alert-success'>". Raptor::get_string('admin_saved') ."</div>";
            }

            $mods = $class->getModules();

            $skip = array('.', '..', '.htaccess', '.conf');
            $files = scandir(MODS_ROOT);
            foreach ($files as $file) 
			{
                if (!in_array($file, $skip)) 
				{
                    $status = in_array($file, $mods) ? "ON" : "OFF";
                    echo "<tr><td> <b><font size=3>" . $file . "</font></b> </td><td> <b>" . $status . "</b> </td><td> [<a href='?enable=" . $file . "'>". Raptor::get_string('enable') ."</a>] </td><td> [<a href='?disable=" . $file . "'>". Raptor::get_string('disable') ."</a>] </td></tr>";
                }
            }
            ?>

        </tbody>
    </table>
</div>