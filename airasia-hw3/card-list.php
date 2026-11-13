<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/validation.php';

$messages = [
    'added'   => 'Gift card added.',
    'deleted' => 'Gift card deleted.',
];
$notice = $messages[$_GET['msg'] ?? ''] ?? null;

$cards = get_db()
    ->query('SELECT card_id, card_name, points_required, card_image FROM GIFTCARD ORDER BY card_name')
    ->fetchAll();

$pageTitle = 'Gift Cards';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/nav.php';
?>

  <main>
    <div class="list-header">
      <h2>Gift Cards</h2>
      <a class="btn" href="card-add.php">Add Gift Card</a>
    </div>

    <?php if ($notice): ?>
      <p class="alert alert-success" role="status"><?= h($notice) ?></p>
    <?php endif; ?>

    <?php if (!$cards): ?>
      <p class="panel">There are no gift cards yet. <a href="card-add.php">Add the first one.</a></p>
    <?php else: ?>
      <ul class="card-grid">
        <?php foreach ($cards as $card): ?>
          <?php $url = 'card-details.php?card_id=' . (int) $card['card_id']; ?>
          <li class="card-item">
            <a href="<?= h($url) ?>"><img src="images/<?= h(basename($card['card_image'])) ?>" alt="<?= h($card['card_name']) ?>"></a>
            <h3><a href="<?= h($url) ?>"><?= h($card['card_name']) ?></a></h3>
            <p class="points"><?= h(number_format((int) $card['points_required'])) ?> points</p>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
