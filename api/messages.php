<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/config.php';

function readMessages(): array
{
    $raw = file_get_contents(MESSAGES_FILE);
    $data = json_decode($raw ?: '[]', true);

    return is_array($data) ? $data : [];
}

function writeMessages(array $messages): void
{
    $fp = fopen(MESSAGES_FILE, 'c+');
    if ($fp === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Impossible d\'écrire les messages.']);
        exit;
    }

    flock($fp, LOCK_EX);
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($messages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
}

function jsonError(string $message, int $code = 400): void
{
    http_response_code($code);
    echo json_encode(['error' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

function strLenUtf8(string $value): int
{
    return function_exists('mb_strlen')
        ? mb_strlen($value, 'UTF-8')
        : strlen($value);
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'GET') {
    $since = isset($_GET['since']) ? (int) $_GET['since'] : 0;
    $messages = readMessages();

    if ($since > 0) {
        $messages = array_values(array_filter(
            $messages,
            static fn (array $m): bool => ($m['id'] ?? 0) > $since
        ));
    }

    echo json_encode(['messages' => $messages], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input') ?: '', true);

    if (!is_array($input)) {
        jsonError('Corps de requête invalide.');
    }

    $pseudo = trim((string) ($input['pseudo'] ?? ''));
    $text = trim((string) ($input['message'] ?? ''));

    if ($pseudo === '') {
        jsonError('Le pseudo est obligatoire.');
    }
    if (strLenUtf8($pseudo) > MAX_PSEUDO_LENGTH) {
        jsonError('Pseudo trop long (max ' . MAX_PSEUDO_LENGTH . ' caractères).');
    }
    if ($text === '') {
        jsonError('Le message ne peut pas être vide.');
    }
    if (strLenUtf8($text) > MAX_MESSAGE_LENGTH) {
        jsonError('Message trop long (max ' . MAX_MESSAGE_LENGTH . ' caractères).');
    }

    $messages = readMessages();
    $nextId = 1;
    if ($messages !== []) {
        $last = end($messages);
        $nextId = (int) ($last['id'] ?? 0) + 1;
    }

    $message = [
        'id' => $nextId,
        'pseudo' => $pseudo,
        'message' => $text,
        'created_at' => date('c'),
    ];

    $messages[] = $message;

    if (count($messages) > MAX_MESSAGES_STORED) {
        $messages = array_slice($messages, -MAX_MESSAGES_STORED);
    }

    writeMessages($messages);

    echo json_encode(['message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Méthode non autorisée.'], JSON_UNESCAPED_UNICODE);
