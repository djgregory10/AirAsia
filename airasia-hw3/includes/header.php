<?php
// Shared page opening: document setup, stylesheet link, site banner.
// Pages set $pageTitle before including this file.
require_once __DIR__ . '/validation.php';
$pageTitle = $pageTitle ?? 'Gift Cards';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= h($pageTitle) ?> - AirAsia Rewards Redemption System</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <header class="site-header">
    <h1>AirAsia Rewards Redemption System</h1>
  </header>
