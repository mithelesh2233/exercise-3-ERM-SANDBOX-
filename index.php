<?php
require_once 'db.php';
require_once 'openai_call.php';
require_once 'prompt.php';

$output = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['situation'])) {
    $situation = trim($_POST['situation']);

    $promptText = getPrompt($situation);

    $messages = [
        ["role" => "user", "content" => $promptText]
    ];
    $output = callOpenAI($messages);

    $stmt = $conn->prepare("INSERT INTO situations (situation_text, api_output) VALUES (?, ?)");
    $stmt->bind_param("ss", $situation, $output);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Situation Rewriter</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; padding: 0 20px; background: #f9f9f9; }
        h2 { color: #333; }
        p { color: #666; }
        textarea { width: 100%; height: 100px; padding: 10px; font-size: 15px; border: 1px solid #ccc; border-radius: 6px; }
        button { margin-top: 10px; padding: 10px 24px; background: #4A90E2; color: white; border: none; border-radius: 6px; font-size: 15px; cursor: pointer; }
        button:hover { background: #357ABD; }
        .output-box { margin-top: 30px; background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #ddd; white-space: pre-wrap; font-size: 15px; line-height: 1.7; }
    </style>
</head>
<body>

    <h2>AI Situation Rewriter</h2>
    <p>Type a short situation (1-2 sentences) and AI will rewrite it clearly with 3 bullet points.</p>

    <form method="POST">
        <textarea name="situation" placeholder="e.g. Our team missed the deadline because of unclear requirements."><?= htmlspecialchars($_POST['situation'] ?? '') ?></textarea>
        <br>
        <button type="submit">Rewrite with AI</button>
    </form>

    <?php if ($output): ?>
        <div class="output-box">
            <strong>AI Output:</strong><br><br>
            <?= nl2br(htmlspecialchars($output)) ?>
        </div>
    <?php endif; ?>

</body>
</html>