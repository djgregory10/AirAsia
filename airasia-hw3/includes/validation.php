<?php
// Reusable validation and safe-output helpers for Gift Card data.

// Allowed card types and the image file used for each type.
const GIFT_CARD_TYPES = [
    'Shopping'      => 'card-shopping.svg',
    'Dining'        => 'card-dining.svg',
    'Travel'        => 'card-travel.svg',
    'Entertainment' => 'card-entertainment.svg',
];

const CARD_NAME_MAX_LENGTH = 100;
const CARD_VALUE_MAX       = 10000;
const POINTS_MAX           = 1000000;

/** Escape a value for safe display in HTML. */
function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Return the identifier as a positive integer, or null if it is not valid. */
function validate_card_id($value): ?int
{
    if (!is_string($value) && !is_int($value)) {
        return null;
    }
    $value = trim((string) $value);
    if (!preg_match('/^[1-9][0-9]{0,17}$/', $value)) {
        return null;
    }
    return (int) $value;
}

/**
 * Validate Gift Card form input.
 * Returns ['errors' => [field => message], 'clean' => [...cleaned values...]].
 * An empty 'errors' array means the data is valid.
 */
function validate_gift_card(array $input): array
{
    $errors = [];

    // Card Name: required, reasonable length
    $name = isset($input['card_name']) && is_string($input['card_name']) ? trim($input['card_name']) : '';
    if ($name === '') {
        $errors['card_name'] = 'Card Name is required.';
    } elseif (mb_strlen($name) > CARD_NAME_MAX_LENGTH) {
        $errors['card_name'] = 'Card Name must be ' . CARD_NAME_MAX_LENGTH . ' characters or fewer.';
    }

    // Card Type: must be one of the allowed values
    $type = isset($input['card_type']) && is_string($input['card_type']) ? $input['card_type'] : '';
    if (!array_key_exists($type, GIFT_CARD_TYPES)) {
        $errors['card_type'] = 'Please choose a valid Card Type.';
    }

    // Card Value: positive number, at most two decimal places
    $value = isset($input['card_value']) && is_string($input['card_value']) ? trim($input['card_value']) : '';
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $value)) {
        $errors['card_value'] = 'Card Value must be a number such as 25 or 25.00.';
    } elseif ((float) $value <= 0) {
        $errors['card_value'] = 'Card Value must be greater than zero.';
    } elseif ((float) $value > CARD_VALUE_MAX) {
        $errors['card_value'] = 'Card Value must be ' . number_format(CARD_VALUE_MAX) . ' or less.';
    }

    // Points Required: positive whole number
    $points = isset($input['points_required']) && is_string($input['points_required']) ? trim($input['points_required']) : '';
    if (!preg_match('/^\d+$/', $points)) {
        $errors['points_required'] = 'Points Required must be a whole number.';
    } elseif ((int) $points <= 0) {
        $errors['points_required'] = 'Points Required must be greater than zero.';
    } elseif ((int) $points > POINTS_MAX) {
        $errors['points_required'] = 'Points Required must be ' . number_format(POINTS_MAX) . ' or less.';
    }

    return [
        'errors' => $errors,
        'clean'  => [
            'card_name'       => $name,
            'card_type'       => $type,
            'card_value'      => $value,
            'points_required' => $points,
        ],
    ];
}

/** Image file name for a card type (falls back to the Shopping image). */
function card_image_for_type(string $type): string
{
    return GIFT_CARD_TYPES[$type] ?? GIFT_CARD_TYPES['Shopping'];
}
