<?php
if (isset($_POST['edit'])) 
{
    Database::Edit('wiki_articles', array(
        'content' => $_POST['edit']
    ));
}

if (isset($_POST['add'])) 
{
    Database::Insert('wiki_articles', array(
        'content' => $_POST['add'],
        'type' => 'default',
        'title' => $_POST['title'],
        'alias' => $_POST['alias'],
    ));
}

if (isset($_GET['edit'])) 
{
    $content = Database::GetOne('wiki_articles', array('alias' => $_GET['edit']));
    echo "<form action='' method='POST'>
        <input type='hidden' name='file' value='" . $_GET['edit'] . "'>
        <textarea rows=15 cols=105 name='edit'>" . $content['content'] . "</textarea> <br>
        <button type='submit' class='btn btn-default'>Сохранить</button>
        </form>
        <hr>";
} 
else 
{
    echo "";
}

if (isset($_GET['remove'])) 
{
    $content = Database::Remove('wiki_articles', array('alias' => $_GET['remove']));
} 
else 
{
    echo "";
}

if (isset($_GET['add'])) 
{
    echo "<form action='' method='POST'>
        <label for='title'>Название статьи</label>
        <input id='title' type='text' style='width:635px;' name='title'><br>
        <label for='title'>Алиас (только английские буквы)</label>
        <input id='title' type='text' style='width:513px;' name='alias'><br>
        <textarea rows=15 cols=105 name='add'></textarea> <br>
        <button type='submit' class='btn btn-success'>Добавить</button>
        </form>
        <hr>";
} 
else 
{
    echo "";
}
?>
<h2>Статьи</h2>
<h5>Статьи в справочнике: на данной странице их можно создавать, редактировать, читать и удалять<br>
    <br>
    <hr>
    <a href="?add=1" style="margin-top: -20px;">Новая статья</a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <td>Статья</td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>

                <?php
                $articles = Database::Get('wiki_articles');
                foreach ($articles as $item) 
				{
                    echo "<tr><td> <b><font size=3>" . $item['title'] . "</font></b> </td><td> <a href='?edit=" . $item['alias'] . "'>Редактировать</a> </td><td> <a href='?remove=" . $item['alias'] . "'>Удалить</a> </td></tr>";
                }
                ?>

            </tbody>
        </table>
    </div>