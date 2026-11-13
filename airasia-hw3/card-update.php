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

$errors = [];
if ($card) {
    // Start with the stored values; replace them with submitted values on POST.
    $values = [
        'card_name'       => $card['card_name'],
        'card_type'       => $card['card_type'],
        'card_value'      => number_format((float) $card['card_value'], 2, '.', ''),
        'points_required' => (string) $card['points_required'],
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $result = validate_gift_card($_POST);
        $errors = $result['errors'];
        $values = $result['clean'];

        if (!$errors) {
            $stmt = get_db()->prepare(
                'UPDATE GIFTCARD
                    SET card_name = :name, card_type = :type, card_value = :value,
                        points_required = :points, card_image = :image
                  WHERE card_id = :id'
            );
            $stmt->execute([
                ':name'   => $values['card_name'],
                ':type'   => $values['card_type'],
                ':value'  => (float) $values['card_value'],
                ':points' => (int) $values['points_required'],
                ':image'  => card_image_for_type($values['card_type']),
                ':id'     => $cardId,
            ]);
            header('Location: card-details.php?card_id=' . $cardId . '&msg=updated');
            exit;
        }
    }
}

$pageTitle = 'Update Gift Card';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

  <main>
    <div class="panel">
      <h2>Update Gift Card</h2>

      <?php if (!$card): ?>
        <p class="alert alert-error" role="alert"><?= h($problem) ?></p>
        <div class="actions">
          <a class="btn btn-secondary" href="card-list.php">Return to Gift Card List</a>
        </div>
      <?php else: ?>
        <?php if ($errors): ?>
          <div class="alert alert-error" role="alert">
            <p>Please fix the following:</p>
            <ul>
              <?php foreach ($errors as $message): ?>
                <li><?= h($message) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form action="card-update.php?card_id=<?= (int) $cardId ?>" method="post" novalidate>
          <label for="card-name">Card Name</label>
          <input type="text" id="card-name" name="card_name" maxlength="100" required value="<?= h($values['card_name']) ?>">

          <label for="card-type">Card Type</label>
          <select id="card-type" name="card_type" required>
            <option value="">Select a type</option>
            <?php foreach (array_keys(GIFT_CARD_TYPES) as $type): ?>
              <option value="<?= h($type) ?>"<?= $values['card_type'] === $type ? ' selected' : '' ?>><?= h($type) ?></option>
            <?php endforeach; ?>
          </select>

          <label for="card-value">Card Value ($)</label>
          <input type="number" id="card-value" name="card_value" min="0.01" max="10000" step="0.01" required value="<?= h($values['card_value']) ?>">

          <label for="points-required">Points Required</label>
          <input type="number" id="points-required" name="points_required" min="1" max="1000000" step="1" required value="<?= h($values['points_required']) ?>">

          <div class="actions">
            <button type="submit">Update Gift Card</button>
            <a class="btn btn-secondary" href="card-details.php?card_id=<?= (int) $cardId ?>">Cancel</a>
          </div>
        </form>
      <?php endif; ?>
    </div>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
