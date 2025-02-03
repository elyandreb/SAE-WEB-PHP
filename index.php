<?php

try {
    $action = $_GET['action'] ?? 'home';
    require_once __DIR__ . '/templates/quiz.php';
    $action = 'showQuiz';


?>