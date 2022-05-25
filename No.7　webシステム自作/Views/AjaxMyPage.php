<?php
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();

$albums = $controller->AjaxMyPage();
echo json_encode($albums);
?>
