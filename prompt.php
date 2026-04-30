<?php
require_once 'db.php';

function getPrompt($situation) {
    global $conn;

    $result = $conn->query("SELECT prompt_text FROM prompt_template ORDER BY id DESC LIMIT 1");
    $row = $result->fetch_assoc();

    $prompt = str_replace("(Situation)", $situation, $row['prompt_text']);
    return $prompt;
}
?>