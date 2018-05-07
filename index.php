<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Страница авторизации</title>
    </head>
    <body>
        <?php
			//print_r($_SESSION);			
			require_once 'C:\xampp\htdocs\Dueling\Table.php';
			scriptTimer::startTime();
			session_start();
			session_destroy();
			//Table::battleEnds(33);
        ?>
		<form method = 'post' action = 'mainmenu.php' style = "width: 320px;">
		<div style = "margin-bottom: 5px;">
			Введите имя: <input required type = 'text' name = 'username' style = "float: right;">
		</div>
		<div style = "margin-bottom: 5px;">
			Введите пароль: <input required type = 'password' name = 'pass' style = "float: right;">
		</div>
		<div>
			<input type = 'submit'>
		</div>
		</form>
    </body>
</html>
<?php
	scriptTimer::endTime();
	echo scriptTimer::getTime();
	Table::showQueries();
?>
