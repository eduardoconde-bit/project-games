<?php
require_once(__DIR__."/services/FileService.php");
require_once(__DIR__."/controller/GameController.php");
require_once(__DIR__."/dao/gameDAO.php");

$page = file_get_contents("./test.html");
$page = sprintf($page, "Luis");
echo $page;

