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
		public function show($type){
                    echo "<div style = 'border: 2px solid black; width:320px; margin-bottom: 5px; padding: 5px'>";
                    if($type == 'o'){
                            $nameField = "Имя противника";
			}
			else if ($type == 's'){
                            $nameField = "Ваше имя";
			}
                    echo "<p>$nameField:$this->name</p>";
                    echo "<p>Урон:$this->damage<p>";
                    $currentHealthPercent = $this->current_health / $this->health * 100;
                    echo "<div style = 'height: 20px; background:gray;'>
                            <div style = 'background:red; width:$currentHealthPercent%; height: 20px;'></div>
                        </div>
                        <div>
		           $this->current_health \ $this->health
                        </div>"
                            . "</div>";
		}
	}
	
?>