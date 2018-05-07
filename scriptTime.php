<?php
	class scriptTimer{
		private static $startTime;
		private static $endTime;
		public static function startTime(){
			self::$startTime = microtime(true);
		}
		public static function endTime(){
			self::$endTime = microtime(true);
		}
		public static function getTime(){
			return "page: " . round((self::$endTime - self::$startTime)*1000) . " ms, ";
		}
	}
?>