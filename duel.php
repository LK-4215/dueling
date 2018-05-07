<?php
	require_once ('C:\xampp\htdocs\Dueling\Table.php');
	scriptTimer::startTime();
	
	session_start();
	$player_id = $_SESSION['authorized_id'];
	$rating = Table::getRating($player_id);
	echo "Ваш рейтинг: $rating<br>";
?>
<a href = "searching.php">Начать дуэль</a><br>
<?php
	scriptTimer::endTime();
	echo scriptTimer::getTime();
	Table::showQueries();
?>