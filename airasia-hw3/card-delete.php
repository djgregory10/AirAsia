<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/validation.php';

$cardId = validate_card_id($_GET['card_id'] ?? null);
$card = null;
$problem = null;

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

// Delete only after a POST confirmation, never from a GET request.
if ($card && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = get_db()->prepare('DELETE FROM GIFTCARD WHERE card_id = :id');
    $stmt->execute([':id' => $cardId]);
    header('Location: card-list.php?msg=deleted');
    exit;
}

$pageTitle = 'Delete Gift Card';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

  <main>
    <div class="panel">
      <h2>Delete Gift Card</h2>

      <?php if (!$card): ?>
        <p class="alert alert-error" role="alert"><?= h($problem) ?></p>
        <div class="actions">
          <a class="btn btn-secondary" href="card-list.php">Return to Gift Card List</a>
        </div>
      <?php else: ?>
        <p class="alert alert-warning" role="alert">
          Are you sure you want to delete <strong><?= h($card['card_name']) ?></strong>? This cannot be undone.
        </p>
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
        <form action="card-delete.php?card_id=<?= (int) $cardId ?>" method="post">
          <div class="actions">
            <button type="submit" class="btn-danger">Yes, Delete Gift Card</button>
            <a class="btn btn-secondary" href="card-details.php?card_id=<?= (int) $cardId ?>">Cancel</a>
          </div>
        </form>
      <?php endif; ?>
    </div>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
