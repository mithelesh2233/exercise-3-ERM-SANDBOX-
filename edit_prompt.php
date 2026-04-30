<?php
require_once 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_prompt = trim($_POST['prompt_text']);
    $stmt = $conn->prepare("UPDATE prompt_template SET prompt_text = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("s", $new_prompt);
    $stmt->execute();
    $message = "Prompt updated successfully!";
}

$result = $conn->query("SELECT prompt_text FROM prompt_template ORDER BY id DESC LIMIT 1");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Prompt</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; padding: 0 20px; background: #f9f9f9; }
        h2 { color: #333; }
        p { color: #666; }
        textarea { width: 100%; height: 150px; padding: 10px; font-size: 15px; border: 1px solid #ccc; border-radius: 6px; }
        button { margin-top: 10px; padding: 10px 24px; background: #4A90E2; color: white; border: none; border-radius: 6px; font-size: 15px; cursor: pointer; }
        button:hover { background: #357ABD; }
        .success { color: green; margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Edit AI Prompt</h2>
    <p>Use <strong>(Situation)</strong> as the placeholder where the user's text will be inserted.</p>

    <?php if ($message): ?>
        <p class="success"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <textarea name="prompt_text"><?= htmlspecialchars($row['prompt_text']) ?></textarea>
        <br>
        <button type="submit">Save Prompt</button>
    </form>

</body>
</html>