# Test Report - HW3 Gift Card Management

**Environment:** PHP 8.3.6 built-in server (`php -S`), SQLite via PDO, tested 2026-11-13. HTTP behavior was checked with `curl` and page appearance with a headless Chromium screenshot. The database was rebuilt from `schema.sql` and `seed.sql` after testing so `rewards.db` ships in its original state (5 ACCOUNT, 20 GIFTCARD).

| ID | Test | Status |
|---|---|---|
| T01 | Validation test harness | Pass |
| T02 | View Gift Card List | Pass |
| T03 | View Gift Card Details | Pass |
| T04 | Add Gift Card | Pass |
| T05 | Update Gift Card | Pass |
| T06 | Delete Gift Card | Pass |
| T07 | Invalid or altered input handling | Pass |
| T08 | Invalid Gift Card identifier handling | Pass |

## T01 - Validation test harness
- **Steps and input:** Opened `tests/validation-tests.php`. It runs 12 Gift Card data tests (valid data, blank/overlong name, invalid/blank type, zero/negative/non-numeric value, zero/negative/decimal/non-numeric points) and 7 identifier tests. It does not use the database.
- **Expected result:** Valid data passes; every invalid case fails on the expected field; every test shows PASS.
- **Actual result:** Page reports "19 of 19 tests passed" with PASS on all 19 rows.
- **Status:** Pass

## T02 - View Gift Card List
- **Steps and input:** Opened `card-list.php`.
- **Expected result:** All seeded cards are listed with image, Card Name, and Points Required; name and image link to `card-details.php?card_id=N`; an Add Gift Card link to `card-add.php` is present.
- **Actual result:** 20 cards displayed, sorted by name, each showing image, name, and formatted points (for example "2,500 points"). Links and the Add Gift Card button are present.
- **Status:** Pass

## T03 - View Gift Card Details
- **Steps and input:** Opened `card-details.php?card_id=1`.
- **Expected result:** Read-only display of image, name, type, value, and points, with Update, Delete, and Return to List links.
- **Actual result:** HTTP 200. Showed Amazon Gift Card, Shopping, $25.00, 2,500 points, with links to `card-update.php?card_id=1`, `card-delete.php?card_id=1`, and `card-list.php`. No form fields on the page.
- **Status:** Pass

## T04 - Add Gift Card
- **Steps and input:** POSTed to `card-add.php` with name "Test Card", type "Dining", value 15.50, points 1550.
- **Expected result:** Record inserted; redirect to `card-list.php`; new card appears.
- **Actual result:** HTTP 302 to `card-list.php?msg=added`; list showed the "Gift card added." message and the card count went from 20 to 21. The form has no Card ID input.
- **Status:** Pass

## T05 - Update Gift Card
- **Steps and input:** Opened `card-update.php?card_id=21`, then POSTed name "Test Card Renamed", type "Travel", value 20, points 2000.
- **Expected result:** Form is prepopulated; update succeeds; user lands on the details page showing the new values.
- **Actual result:** Form showed name "Test Card", Dining selected, 15.50, and 1550. The POST returned HTTP 302 to `card-details.php?card_id=21&msg=updated`, which showed "Gift card updated." with Test Card Renamed, Travel, $20.00, 2,000 points.
- **Status:** Pass

## T06 - Delete Gift Card
- **Steps and input:** Requested `card-delete.php?card_id=21` with GET, checked the list count, then sent a POST to the same URL.
- **Expected result:** GET only shows a confirmation naming the card and deletes nothing; POST deletes and returns to the list.
- **Actual result:** GET returned 200 with "Are you sure you want to delete Test Card Renamed?" and the list still held 21 cards. POST returned 302 to `card-list.php?msg=deleted`, the list returned to 20 cards, and `card-details.php?card_id=21` then returned 404.
- **Status:** Pass

## T07 - Invalid or altered input handling
- **Steps and input:** POSTed to `card-add.php` with a blank name, type "Hacked", value -1, and points 1.5. Also POSTed a name of `<script>alert(1)</script>` with value 0. Also POSTed a non-numeric value to `card-update.php?card_id=21`.
- **Expected result:** No record is saved; helpful error messages appear; submitted text is displayed escaped.
- **Actual result:** The first POST re-displayed the form with four errors (name required, invalid type, invalid value, points must be a whole number). The script text was echoed as `&lt;script&gt;...` and was not executed. The update POST showed the Card Value error and did not change the record.
- **Status:** Pass

## T08 - Invalid Gift Card identifier handling
- **Steps and input:** Requested `card-details.php`, `card-update.php`, and `card-delete.php` with `card_id` values `abc`, `0`, `-3`, `1.5`, `1 OR 1=1`, an empty value, and `9999` (nonexistent).
- **Expected result:** Invalid values are rejected before any query; nonexistent IDs show a "not found" message; no errors or stack traces appear.
- **Actual result:** All three pages returned HTTP 400 with a friendly message for the invalid values and HTTP 404 for 9999, each with a link back to the list.
- **Status:** Pass

## Additional testing (database and application integrity)

These extra checks were run on a scratch copy of the project so the shipped `rewards.db` stayed unchanged. All passed.

| Area | Test and result |
|---|---|
| Database integrity | `PRAGMA integrity_check` returned "ok"; ACCOUNT has 5 rows and GIFTCARD has 20; all cards have a positive value and points; all four image files exist; every sample account password verifies against its stored hash. |
| Database constraints | The database rejected 8 invalid inserts on its own: bad card type, zero value, negative points, NULL name, duplicate username, duplicate email, invalid role, negative points balance. |
| Reproducible rebuild | `php database/build.php` rebuilt a database whose GIFTCARD data matched the shipped `rewards.db` exactly. |
| Shared templates and PDO | Every page uses `require_once` for the header, nav, and footer, has no repeated `<html>` or stylesheet tags, and none creates its own PDO connection (only `includes/db.php` does). |
| Link crawl | All six pages (five application pages plus the test harness) returned HTTP 200, and every internal link, image, and form action resolved. |
| CRUD with database checks | Add changed the count from 20 to 21 and stored the right values. Update changed the stored row. A GET to the delete page deleted nothing. A POST delete removed the row (count back to 20), and a second POST returned 404. |
| SQL injection | `DROP TABLE` and `OR 1=1` in the URL ID and in form fields changed no data. SQL text entered as a card name was stored as plain text and the table stayed intact. |
| Tampering | A `card_id` sent in the POST body was ignored in favor of the URL's ID. Array-valued form fields and a script tag in `?msg=` caused no errors or script output. |
| Output escaping | A name containing `'`, `"`, `&`, and `<b>` was stored as typed and displayed escaped on the list, details, and update pages. |
| Boundary values | Accepted: value 0.01, value 10,000, points 1, points 1,000,000, a 100-character name, and a Unicode name. Rejected: value over 10,000, three decimal places, points over 1,000,000, a 101-character name, and an extremely large points value. |
| PHP errors | No warnings, notices, or fatal errors appeared in the server log. |

**Note for HW4:** the PHP development server serves `database/rewards.db` directly at `/database/rewards.db`. The HW3 folder layout requires the database there, but before storing real credentials in HW4 the database should be moved outside the web root or that folder blocked.

## Defects found
None in the tested behavior. During development a leftover redundant expression in `validate_gift_card()` was cleaned up before testing; it did not affect results.
