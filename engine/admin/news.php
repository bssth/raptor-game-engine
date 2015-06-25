<h2>Новости</h2>
<br>
<hr>

<?php
if (isset($_POST['title'])) {
    Database::Edit("news", array("_id" => toId($_GET['edit'])), $_POST);
    echo '<div class="alert alert-success">Скрипт успешно отредактирован</div>';
}
if (isset($_GET['edit'])) {
    $array = Database::GetOne("news", array("_id" => toId($_GET['edit'])));
    echo "<form action='' method='POST'>
		<input class='form-control' name='title' value='" . $array['title'] . "' placeholder='Заголовок'>
		<textarea rows=15 cols=105 placeholder='Анонс (краткое описание)' name='short'>" . $array['short'] . "</textarea> <br>
        <textarea rows=15 cols=105 placeholder='Полный текст' name='full'>" . $array['full'] . "</textarea> <br>
        <button type='submit' class='btn btn-default'>Сохранить</button>
        </form>
        <hr>";
}
?>


<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <td>Заголовок</td>
                <td></td>
            </tr>
        </thead>
        <tbody>

            <?php
            $arrays = Database::Get("news", array());
            foreach ($arrays as $array) {
                echo "<tr><td> <b><font size=3>" . $array['title'] . "</font></b> </td><td> <a href='?edit=" . $array['_id'] . "'>Редактировать</a> </td></tr>";
            }
            ?>

        </tbody>
    </table>
</div>