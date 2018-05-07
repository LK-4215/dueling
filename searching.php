<?php
	require_once ('C:\xampp\htdocs\Dueling\Table.php');
	scriptTimer::startTime();
	session_start();
	$player_id = $_SESSION['authorized_id'];
	
	if(empty($_SESSION['searchTimer'])){
		$_SESSION['searchTimer'] = 1;
	}
	else {
		$_SESSION['searchTimer']++;
	}
	if(empty($_SESSION['flag'])){
		$_SESSION['flag'] = false;
	}
	
	if(!$_SESSION['flag']){
		Table::setPlayerReady($player_id, 1);
		$_SESSION['flag'] = true;
	}
	
	if($_SESSION['searchTimer'] < 100 && empty($_SESSION['opponent_id'])){
		$opponent_id = Table::getPlayerReady($player_id);
		
		if($opponent_id){		
			$_SESSION['opponent_id'] = $opponent_id;
			$_SESSION['countdownTimer'] = 30;
		}
		else header("refresh: 5;");
	}
	else if($_SESSION['searchTimer'] > 100){
		echo "player not found";
		$_SESSION['searchTimer'] = 0;
	}
	if(!empty($_SESSION['countdownTimer'])){
		$countDown = $_SESSION['countdownTimer'];
		echo "Ваш противник найден<br>";
		echo "До начала битвы: $countDown секунд<br>";
		$_SESSION['countdownTimer']--;
		header("refresh: 1;");
	}
	if(isset($_SESSION['countdownTimer']) && $_SESSION['countdownTimer'] == 0){
		Table::setPlayerReady($player_id, 0);	
		header("refresh: 0; url=battle.php");
	}
	scriptTimer::endTime();
	echo scriptTimer::getTime();
	Table::showQueries();
?>