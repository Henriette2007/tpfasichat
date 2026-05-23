<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP Fasi Chat</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main class="chat-app">
        <header class="chat-header">
            <h1>TP Fasi Chat</h1>
            <p class="subtitle">Chat en temps réel (PHP + JSON)</p>
        </header>

        <section id="login-panel" class="panel login-panel">
            <label for="pseudo-input">Choisissez un pseudo</label>
            <div class="login-row">
                <input
                    type="text"
                    id="pseudo-input"
                    maxlength="32"
                    placeholder="Votre pseudo"
                    autocomplete="username"
                >
                <button type="button" id="join-btn">Rejoindre</button>
            </div>
        </section>

        <section id="chat-panel" class="panel chat-panel hidden">
            <div class="chat-meta">
                <span>Connecté en tant que <strong id="current-pseudo"></strong></span>
                <button type="button" id="leave-btn" class="btn-link">Quitter</button>
            </div>

            <ul id="messages" class="messages" aria-live="polite"></ul>

            <form id="message-form" class="message-form">
                <input
                    type="text"
                    id="message-input"
                    maxlength="500"
                    placeholder="Écrivez un message…"
                    autocomplete="off"
                    required
                >
                <button type="submit">Envoyer</button>
            </form>
        </section>

        <p id="status" class="status" role="status"></p>
    </main>

    <script src="assets/js/chat.js"></script>
</body>
</html>
