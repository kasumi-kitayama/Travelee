<?php
require_once(ROOT_PATH .'Controllers/Controller.php');
$controller = new Controller();
$controller->AjaxMyQuestions();

$answer = [
    'user_id' => $_POST['user_id'],
    'question_id' => $_POST['question_id'],
    'content' => $_POST['content'],
    'created_at' => $_POST['created_at']
];
echo json_encode($answer);
?>
