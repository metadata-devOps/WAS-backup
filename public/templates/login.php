<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sign in</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 320px; }
        h1 { margin-bottom: 1.5rem; font-size: 1.5rem; text-align: center; color: #1a1a1a; }
        input { width: 100%; padding: 0.75rem; margin-bottom: 1rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 0.75rem; background: #007bff; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
        button:hover { background: #0056b3; }
        .error { color: #dc3545; text-align: center; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Sign in</h1>
        <?php if (isset($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="/login">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
    </div>
</body>
</html>
