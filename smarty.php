<?php
require_once ('C:\smarty\smarty-3.1.30\libs\Smarty.class.php');
$smarty = new Smarty();

$smarty->template_dir = 'C:\xampp\htdocs\Dueling\templates';
$smarty->compile_dir = 'C:\xampp\htdocs\Dueling\templates_c';
$smarty->config_dir = 'C:\xampp\htdocs\Dueling\configs';
$smarty->cache_dir = 'C:\xampp\htdocs\Dueling\cache';

?>