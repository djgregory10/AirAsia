<?php
// Rebuilds database/rewards.db from schema.sql and seed.sql.
// Usage (command line only):  php database/build.php
if (PHP_SAPI !== 'cli') {
    exit('Run this script from the command line.');
}

$dbFile = __DIR__ . '/rewards.db';
if (file_exists($dbFile)) {
    unlink($dbFile);
}

$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec(file_get_contents(__DIR__ . '/schema.sql'));
$pdo->exec(file_get_contents(__DIR__ . '/seed.sql'));

$accounts = $pdo->query('SELECT COUNT(*) FROM ACCOUNT')->fetchColumn();
$cards    = $pdo->query('SELECT COUNT(*) FROM GIFTCARD')->fetchColumn();
echo "Built rewards.db: $accounts ACCOUNT rows, $cards GIFTCARD rows\n";
