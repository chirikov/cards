function w_file(action)
{
	open_win('<form action="'+action+'" method="post" enctype="multipart/form-data">Путь к файлу: <input type="file" name="photo"> <input type="submit" value="Загрузить" onclick="javascript: document.getElementById(\'wait\').style.display = \'\';"><div id="wait" style="display: none">Подождите...</div></form>');
}