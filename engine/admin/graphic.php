<div class="row">
    <?php
	if (substr($_GET['sel'], 1, 1) == ".") 
	{
        raptor_error("Trying to access '" . SEPARATOR . "Graphics" . SEPARATOR . $_GET['sel'] . "' directory");
        die(Raptor::get_string('error'));
    }
    if (isset($_GET['sel'])) 
	{
        $dir = STATIC_ROOT . SEPARATOR . "Graphics" . SEPARATOR . $_GET['sel'];

        if (isset($_FILES['userfile']['name'])) 
		{
            $imageinfo = getimagesize($_FILES['userfile']['tmp_name']);

            if ($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png') 
			{
                echo '<div class="alert alert-danger">Bad MIME-type</div>';
            } 
			else 
			{
                $uploadfile = $dir . SEPARATOR . basename($_FILES['userfile']['name']);

                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) 
				{
                    echo '<div class="alert alert-success">'. Raptor::get_string('loaded') .'</div>';
                } 
				else 
				{
                    echo '<div class="alert alert-danger">'. Raptor::get_string('error') .'</div>';
                }
            }
        }

        $files = scandir($dir);

        foreach ($files as $file) 
		{
            if ($file == "." or $file == "..") 
			{
                continue;
            }
            echo '<div class="col-lg-2 text-center">
							<div class="panel panel-default">
								<div class="panel-body">
									<img src="/storage/static/Graphics/' . $_GET['sel'] . '/' . $file . '" width=100 height=100>
									<p><a href="/storage/static/Graphics/' . $_GET['sel'] . '/' . $file . '">' . $file . '</a></p>
								</div>
							</div>
				  </div>';
        }
    } 
	else 
	{
        $dir = STATIC_ROOT . SEPARATOR . "Graphics";
        $files = scandir($dir);
        foreach ($files as $file) 
		{
            if ($file == "." or $file == "..") 
			{
                continue;
            }
            echo '<div class="col-lg-2 text-center">
							<div class="panel panel-default">
								<div class="panel-body">
									<a href="?sel=' . $file . '">' . $file . '</a>
								</div>
							</div>
				  </div>';
        }
    }
    ?>

</div>
<div class="well"><div class="form-group"><form name="upload" action="" method="POST" ENCTYPE="multipart/form-data"><label><?=Raptor::get_string('load')?></label><input type="file" name="userfile"><input type="submit" value="<?=Raptor::get_string('load')?>"></form></div></div>