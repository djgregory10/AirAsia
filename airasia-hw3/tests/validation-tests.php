<?php
// Validation test harness. Tests the functions in includes/validation.php only.
// It never touches the database, so it cannot add, change, or delete records.
require_once __DIR__ . '/../includes/validation.php';

$valid = ['card_name' => 'Amazon Gift Card', 'card_type' => 'Shopping', 'card_value' => '25.00', 'points_required' => '2500'];

// Each test: label, input, expected outcome ('pass' = no errors, otherwise the field that must fail)
$tests = [
    ['Valid Gift Card data',                      $valid,                                          'pass'],
    ['Blank Card Name',                           array_merge($valid, ['card_name' => '   ']),     'card_name'],
    ['Card Name longer than 100 characters',      array_merge($valid, ['card_name' => str_repeat('A', 101)]), 'card_name'],
    ['Altered Card Type (not in allowed list)',   array_merge($valid, ['card_type' => 'Hacked']),  'card_type'],
    ['Blank Card Type',                           array_merge($valid, ['card_type' => '']),        'card_type'],
    ['Zero Card Value',                           array_merge($valid, ['card_value' => '0']),      'card_value'],
    ['Negative Card Value',                       array_merge($valid, ['card_value' => '-5']),     'card_value'],
    ['Non-numeric Card Value',                    array_merge($valid, ['card_value' => 'abc']),    'card_value'],
    ['Zero Points Required',                      array_merge($valid, ['points_required' => '0']), 'points_required'],
    ['Negative Points Required',                  array_merge($valid, ['points_required' => '-100']), 'points_required'],
    ['Decimal Points Required',                   array_merge($valid, ['points_required' => '12.5']), 'points_required'],
    ['Non-numeric Points Required',               array_merge($valid, ['points_required' => 'lots']), 'points_required'],
];

$results = [];
foreach ($tests as [$label, $input, $expected]) {
    $errors = validate_gift_card($input)['errors'];
    if ($expected === 'pass') {
        $ok = count($errors) === 0;
        $expectedText = 'Passes validation';
    } else {
        $ok = isset($errors[$expected]);
        $expectedText = 'Fails validation (' . $expected . ')';
    }
    $results[] = [$label, $expectedText, $ok];
}

// Identifier tests
$idTests = [
    ['Gift Card ID "12" is accepted',     '12',        12],
    ['Gift Card ID "abc" is rejected',    'abc',       null],
    ['Gift Card ID "0" is rejected',      '0',         null],
    ['Gift Card ID "-3" is rejected',     '-3',        null],
    ['Gift Card ID "1.5" is rejected',    '1.5',       null],
    ['Gift Card ID "1 OR 1=1" is rejected', '1 OR 1=1', null],
    ['Missing Gift Card ID is rejected',  null,        null],
];
foreach ($idTests as [$label, $input, $expected]) {
    $results[] = [$label, $expected === null ? 'Rejected (null)' : 'Accepted as ' . $expected, validate_card_id($input) === $expected];
}

$passed = count(array_filter($results, fn($r) => $r[2]));
$total = count($results);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Validation Tests - AirAsia Rewards Redemption System</title>
  <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
  <header class="site-header">
    <h1>AirAsia Rewards Redemption System</h1>
  </header>
  <main>
    <h2>Validation Tests</h2>
    <p><?= h($passed) ?> of <?= h($total) ?> tests passed.</p>
    <table class="results">
      <thead>
        <tr><th>#</th><th>Test</th><th>Expected result</th><th>Result</th></tr>
      </thead>
      <tbody>
        <?php foreach ($results as $i => [$label, $expectedText, $ok]): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= h($label) ?></td>
            <td><?= h($expectedText) ?></td>
            <td class="<?= $ok ? 'pass' : 'fail' ?>"><?= $ok ? 'PASS' : 'FAIL' ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>
</body>
</html>
