<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Files</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; color: #333; }
        h1 { border-bottom: 2px solid #eee; padding-bottom: 0.5rem; }
        .box { background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border: 1px solid #dee2e6; margin-top: 2rem; }
        code { background: #e9ecef; padding: 0.2rem 0.4rem; border-radius: 4px; font-size: 90%; }
        pre { background: #272822; color: #f8f8f2; padding: 1rem; border-radius: 4px; overflow-x: auto; }
        button { padding: 0.5rem 1rem; background: #28a745; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
        button:hover { background: #218838; }
    </style>
</head>
<body>
    <h1>Image tools</h1>
    <p>Account: <strong><?= htmlspecialchars(\Core\Auth::user()->isAuthenticated ? 'signed in' : 'guest') ?></strong></p>
    
    <div class="box">
        <h3>Upload</h3>
        <p>Select a JPEG file.</p>
        <form action="/inspect" method="POST" enctype="multipart/form-data">
            <input type="file" name="photo" accept="image/jpeg" required>
            <button type="submit">Analyze</button>
        </form>
    </div>
</body>
</html>
