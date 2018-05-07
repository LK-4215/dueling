<?php
	class Player{
		public $id, $name, $damage, $health, $current_health;
		public function __construct($id, $name, $damage, $health, $current_health){
			$this->id = $id;
			$this->name = $name;
			$this->health = $health;
			$this->current_health = $current_health;
			$this->damage = $damage;
		}
		public function attack($player, $battle){
			$player->dealDamage($this->damage);
			$log = "Вы ударили " . $player->name . " на " . $this->damage;
			Table::addLog($this->id, $battle, $log);
			
			$log = "Вас ударил " . $this->name . " на " . $this->damage;
			Table::addLog($player->id, $battle, $log);
		}
		public function dealDamage($damage){
			$this->current_health -= $damage;
			Table::setPlayerCurrentHealth($this);
			
		}
		public function show($smarty, $type){
			$smarty->assign('name', $this->name);
			$smarty->assign('damage', $this->damage);
			$smarty->assign('currentHealthPercent', $this->current_health / $this->health * 100);
			$smarty->assign('currentHealth', $this->current_health);
			$smarty->assign('health', $this->health);
			if($type == 'o'){
				$smarty->assign('nameField', "Имя противника");
			}
			else if ($type == 's'){
				$smarty->assign('nameField', "Ваше имя");
			}
			$smarty->display('player_info.tpl');
		}
	}
	
?>