<?php
	//класc для отображения лога боя для игрока player
	class LogBattle{
		private $logArray;
		private $index;
		/*private $player_id;*/
		public function __construct(){
			$this->index = 0;
		}
		public function addLog($value){
			$this->logArray[$this->index] = $value;
			$this->index++;
			//print_r($this->logArray);
		}
		public function getLogs(){
			return $this->logArray;
		}
		public function show(){
			echo "<div style='overflow: scroll; height:80px; width:320px'>";
			for($i = 0; $i<$this->index; $i++){
                                $str = $this->logArray[$i];
				echo "<p style = 'margin:0px;'>$str</p>";
			}
			echo "</div>";
		}
	}
?>