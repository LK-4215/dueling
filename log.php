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
			return $this->logArray();
		}
		public function show($smarty){
			$smarty->display("log_begin.tpl");
			for($i = 0; $i<$this->index; $i++){
				$smarty->assign("logstring", $this->logArray[$i]);
				$smarty->display("logstrings.tpl");
			}
			$smarty->display("log_end.tpl");
		}
	}
?>