<?php

declare(strict_types=1);

define('DATA_DIR', __DIR__ . '/data');
define('MESSAGES_FILE', DATA_DIR . '/messages.json');
define('MAX_MESSAGE_LENGTH', 500);
define('MAX_PSEUDO_LENGTH', 32);
define('MAX_MESSAGES_STORED', 200);

if (!is_dir(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}

if (!file_exists(MESSAGES_FILE)) {
    file_put_contents(MESSAGES_FILE, '[]');
}
