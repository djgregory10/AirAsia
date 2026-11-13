<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/validation.php';

$messages = ['updated' => 'Gift card updated.'];
$notice = $messages[$_GET['msg'] ?? ''] ?? null;

$cardId = validate_card_id($_GET['card_id'] ?? null);
$card = null;

if ($cardId === null) {
    http_response_code(400);
    $problem = 'The gift card identifier is not valid.';
} else {
    $stmt = get_db()->prepare('SELECT * FROM GIFTCARD WHERE card_id = :id');
    $stmt->execute([':id' => $cardId]);
    $card = $stmt->fetch();
    if (!$card) {
        http_response_code(404);
        $problem = 'That gift card could not be found.';
    }
}

$pageTitle = 'Gift Card Details';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

  <main>
    <?php if (!$card): ?>
      <div class="panel">
        <h2>Gift Card Details</h2>
        <p class="alert alert-error" role="alert"><?= h($problem) ?></p>
        <div class="actions">
          <a class="btn btn-secondary" href="card-list.php">Return to Gift Card List</a>
        </div>
      </div>
    <?php else: ?>
      <?php if ($notice): ?>
        <p class="alert alert-success" role="status"><?= h($notice) ?></p>
      <?php endif; ?>
      <div class="panel">
        <h2>Gift Card Details</h2>
        <div class="details-layout">
          <img src="images/<?= h(basename($card['card_image'])) ?>" alt="<?= h($card['card_name']) ?>">
          <dl>
            <dt>Card Name</dt>
            <dd><?= h($card['card_name']) ?></dd>
            <dt>Card Type</dt>
            <dd><?= h($card['card_type']) ?></dd>
            <dt>Card Value</dt>
            <dd>$<?= h(number_format((float) $card['card_value'], 2)) ?></dd>
            <dt>Points Required</dt>
            <dd><?= h(number_format((int) $card['points_required'])) ?> points</dd>
          </dl>
        </div>
        <div class="actions">
          <a class="btn" href="card-update.php?card_id=<?= (int) $card['card_id'] ?>">Update Gift Card</a>
          <a class="btn btn-danger" href="card-delete.php?card_id=<?= (int) $card['card_id'] ?>">Delete Gift Card</a>
          <a class="btn btn-secondary" href="card-list.php">Return to Gift Card List</a>
        </div>
      </div>
    <?php endif; ?>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
