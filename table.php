<?php
	require_once ('C:\xampp\htdocs\Dueling\Player.php');
	require_once ('C:\xampp\htdocs\Dueling\scriptTime.php');
	require_once ('C:\xampp\htdocs\Dueling\smarty.php');
	require_once ('C:\xampp\htdocs\Dueling\log.php');
	//класс обертка над mysqli для расчета времени выполнения запросов
	class mysquliCounterable{
		private $sql;
		private $i;
		private $mseconds;
		public function connect($hostname, $username, $pass, $db){
			$this->i = 0;
			$this->sql = new mysqli;
			$this->sql->connect($hostname, $username, $pass, $db);

		}
		public function query($query){
			$this->i++;
			$time_start = microtime(true);
			$result = $this->sql->query($query);
			$time_end = microtime(true);
			$this->mseconds += ($time_end - $time_start)*1000;
			return $result;
		}
		public function clear(){
			$this->i = 0;
			$this->mseconds = 0;
		}
		public function showInfo(){
			$this->mseconds = round($this->mseconds); 
			echo "db:$this->i" . "req";
			echo "($this->mseconds" . "ms)<br>";
			$this->clear();
		}
	}
	//класс для доступа к базе данных
	class Table{
		private static $sql;
		public static function connect($hostname, $username, $pass, $db){
			self::$sql = new mysquliCounterable;
			self::$sql->connect($hostname, $username, $pass, $db);			
		}
		public static function showQueries(){
			self::$sql->showInfo();
		}
		//добавить в таблицу нового игрока и вернуть его айди
		public static function addNewPlayer($name, $pass){
			$query = "INSERT INTO players (name, pass, damage, health, current_health, rating) 
					VALUES ('$name', '$pass', '10', '100', '100', '0')";
			self::$sql->query($query);
			$query = "SELECT MAX(id_player) AS max FROM players";
			$result = self::$sql->query($query);
			$result_array = $result->fetch_assoc();
			return $result_array['max'];
		}
		
		public static function setPlayerReady($id, $value){
			$query = "UPDATE players SET readyforduel=$value WHERE id_player=$id";
			self::$sql->query($query);			
		}
		public static function getPlayerReady($id){
			$query = "SELECT id_player FROM players WHERE readyforduel=1 AND id_player!=$id";
			if($result = self::$sql->query($query)){
				$result_array = $result->fetch_assoc();
				return $result_array['id_player'];
			}
		}
		public static function getPlayerById($id){
			$query = "SELECT * FORM players WHERE player_id = $id";
		}
		public static function getByNamePass($name, $pass){
			$query = "SELECT * FROM players WHERE name = '$name'";
			$result = self::$sql->query($query);
			
			if($result->num_rows == 0 ){
				return Table::addNewPlayer($name, $pass);
			}
			else{
				$result_array = $result->fetch_row();
				//print_r($result_array);
				if($result_array[2] == $pass){
					return $result_array[0];
				}
				else{
					die("Неверный пароль");
				}
			}
		}
		public static function getReadyPlayer(){
			$query = "SELECT * FROM players WHERE readyforduel=1";
			$result = self::$sql->query($query);
			if($result->num_rows == 0){
				return false;
			}
			else {
				$result_array = $result->fetch_assoc();
				$player = new Player($result_array['name'], $result_array['pass'], $result_array['damage'], 
										$result_array['health'], $result_array['current_health'], $result_array['rating']);
				return $player;
			}
			
		}
		//возвращает класс игрока
		public static function getPlayerInfo($id){
			$query = "SELECT id_player, name, damage, health, current_health FROM players WHERE id_player=$id";
			$result = self::$sql->query($query);
			$result_array = $result->fetch_assoc();
			return new Player($result_array['id_player'], $result_array['name'], $result_array['damage'], $result_array['health'], $result_array['current_health']);
		}
		public static function setPlayerCurrentHealth($player){
			$query = "UPDATE players SET current_health=$player->current_health WHERE id_player=$player->id";
			self::$sql->query($query);
		}
		public static function updateCurrentHealth($id){
			
			$query = "SELECT health FROM players WHERE id_player=$id";
			//echo $query;
			$result = self::$sql->query($query);
			//print_r($result);
			$health = $result->fetch_assoc()['health'];
			$query = "UPDATE players SET current_health=$health WHERE id_player=$id";
			self::$sql->query($query);
		}
		public static function damageChange($id, $value){
			$query = "SELECT damage FROM players WHERE id_player=$id";
			$result = self::$sql->query($query);
			$damage = $result->fetch_assoc()['damage'];
			$damage = $damage + $value;
			$query = "UPDATE players SET damage=$damage WHERE id_player=$id";
			self::$sql->query($query);
		}
		public static function healthChange($id, $value){
			$query = "SELECT health FROM players WHERE id_player=$id";
			$result = self::$sql->query($query);
			$health = $result->fetch_assoc()['health'];
			$health++;
			$query = "UPDATE players SET health=$health WHERE id_player=$id";
			self::$sql->query($query);
		}
		public static function ratingChange($id, $value){
			$query = "SELECT rating FROM players WHERE id_player=$id";
			$result = self::$sql->query($query);
			$rating = $result->fetch_assoc()['rating'];
			$rating = $rating + $value;
			$query = "UPDATE players SET rating=$rating WHERE id_player=$id";
			self::$sql->query($query);
		}
		//создать в таблице запись о новой битве, вернуть ее айди и проверить на отсутствие записи о новой битве 
		public static function newBattleStart($id_player1, $id_player2){
			$query = "SELECT id_battle FROM battles WHERE (id_player1=$id_player1 OR id_player2=$id_player1) AND going=1";
			$resultIdBattle = self::$sql->query($query);
			if($resultIdBattle->num_rows == 0){	
				$queryInsert = "INSERT INTO battles (id_player1, id_player2, going) VALUES 
						($id_player1, $id_player2, 1)";
				self::$sql->query($queryInsert);
				$resultIdBattle = self::$sql->query($query);
			}
			$id_battle = $resultIdBattle->fetch_assoc()['id_battle'];
			return $id_battle;
		}
		public static function battleEnds($id_battle){
			$query = "UPDATE battles SET going = 0 WHERE id_battle = $id_battle";
			self::$sql->query($query);
			/*$query = "SELECT id_player1, id_player2 FROM battles WHERE id_battle = $id_battle";
			$result = self::$sql->query($query);*/

		}
		public static function addLog($player, $battle, $log){
			$query = "INSERT INTO logs (id_battle, id_player, log) VALUES ($battle, $player, '$log')";
			self::$sql->query($query);
		}
		public static function getLogInfo($id_player, $id_battle){
			$query = "SELECT log FROM logs WHERE id_player = $id_player AND id_battle = $id_battle";
			$result = self::$sql->query($query);
			//$logs = $result->fetch_assoc();
			//print_r($result);
			if($result->num_rows > 0){
				$logInfo = new LogBattle();
				for($i=0; $i<$result->num_rows; $i++){
					$value = $result->fetch_row();
					$logInfo->addLog($value[0]);
				}
				return $logInfo;
			}
			//$logInfo->show();
			return false;
		}
		public static function getRating($id_player){
			$query = "SELECT rating FROM players WHERE id_player = $id_player";
			$result = self::$sql->query($query);
			return $result->fetch_assoc()['rating'];
		}

	}
	Table::connect('localhost', 'root', '', 'dueling');
	//print_r($smarty);
?>