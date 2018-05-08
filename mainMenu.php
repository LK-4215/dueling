<?php
	require_once 'table_class.php';
	scriptTimer::startTime();
	session_start();
	unset($_SESSION['winner']);
	unset($_SESSION['loser']);
	if($_POST){
		$name = $_POST['username'];
		$pass = $_POST['pass'];
		$player_id = Table::getByNamePass($name, $pass);
		if($player_id){
			$_SESSION['authorized_id'] = $player_id;
		}
	}
?>
<div>
	<a href = "index.php">Выход</a>
	<a href = "duel.php">Дуэли</a>
</div>
<?php
	scriptTimer::endTime();
	echo scriptTimer::getTime();
	Table::showQueries();
?>