<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/validation.php';

$errors = [];
$values = ['card_name' => '', 'card_type' => '', 'card_value' => '', 'points_required' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = validate_gift_card($_POST);
    $errors = $result['errors'];
    $values = $result['clean'];

    if (!$errors) {
        $stmt = get_db()->prepare(
            'INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image)
             VALUES (:name, :type, :value, :points, :image)'
        );
        $stmt->execute([
            ':name'   => $values['card_name'],
            ':type'   => $values['card_type'],
            ':value'  => (float) $values['card_value'],
            ':points' => (int) $values['points_required'],
            ':image'  => card_image_for_type($values['card_type']),
        ]);
        header('Location: card-list.php?msg=added');
        exit;
    }
}

$pageTitle = 'Add Gift Card';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

  <main>
    <div class="panel">
      <h2>Add Gift Card</h2>

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

      <form action="card-add.php" method="post" novalidate>
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
          <button type="submit">Add Gift Card</button>
          <a class="btn btn-secondary" href="card-list.php">Cancel</a>
        </div>
      </form>
    </div>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
