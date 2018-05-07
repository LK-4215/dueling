<?php
	require_once 'C:\xampp\htdocs\Dueling\Table.php';
	scriptTimer::startTime();
	session_start();
	$id_player = $_SESSION['authorized_id'];
	//print_r($_SESSION);
	if(empty($_SESSION['winner'])){
		$_SESSION['winner'] = true;
		Table::ratingChange($id_player, 1);
		Table::damageChange($id_player, 1);
		Table::healthChange($id_player, 1);
		//Table::updateCurrentHealth($id_player);
		//Table::updateCurrentHealth($_SESSION['opponent_id']);	
		unset($_SESSION['opponent_id']);
		unset($_SESSION['countdownTimer']);
		unset($_SESSION['flag']);
		unset($_SESSION['battle_start']);
	}
	echo "Вы выиграли!<br>";
?>
<a href='mainmenu.php'>В главное меню</a><br>
<?php
	scriptTimer::endTime();
	echo scriptTimer::getTime();
	Table::showQueries();
?>