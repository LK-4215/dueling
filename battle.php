<?php
	require_once ('C:\xampp\htdocs\Dueling\Table.php');
	//require_once ('C:\xampp\htdocs\Dueling\player.php');
	class Battle{
		private $player1, $player2, $battle;
		public function initialise(){
			$id1 = $_SESSION['authorized_id'];
			$id2 = $_SESSION['opponent_id'];
			if(empty($_SESSION['battle_start'])){
				Table::updateCurrentHealth($id1);
				Table::updateCurrentHealth($id2);
				$_SESSION['battle_start'] = true;
			}
			$this->battle = Table::newBattleStart($id1, $id2);
			$this->player1 = Table::getPlayerInfo($id1);
			$this->player2 = Table::getPlayerInfo($id2);
		}
		public function attack(){
			$this->player1 = Table::getPlayerInfo($this->player1->id);
			if($this->player1->current_health<1){
				Table::battleEnds($this->battle);
				header("refresh:0; url=lose.php");
			}
			$this->player1->attack($this->player2, $this->battle);
			
			$this->player2 = Table::getPlayerInfo($this->player2->id);
			if($this->player2->current_health<1){
				Table::battleEnds($this->battle);
				header("refresh:0; url=win.php");
			}
		}
		public function show($smarty){
			$logInfo = Table::getLogInfo($this->player1->id, $this->battle);
			$this->player2->show($smarty, 'o');
			$this->player1->show($smarty, 's');
			if($logInfo){
				$logInfo->show($smarty);
			}
		}
	}
	scriptTimer::startTime();
	session_start();
	$battle = new Battle;
	$battle->initialise();
	if(isset($_POST['attack'])){
		$battle->attack();
	}
	$battle->show($smarty);
	scriptTimer::endTime();
	echo scriptTimer::getTime();
	Table::showQueries();	
?>
<form method = 'post' action = 'battle.php'>
	<input type = 'submit' name = 'attack' value = 'attack'>
</form>
