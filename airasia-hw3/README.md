# AirAsia Rewards Redemption System - HW3

Gift Card management built with PHP, SQLite, and PDO. This homework covers Gift Card CRUD only. Login, sessions, authorization, customer management, and redemption are part of HW4.

## Run the application

Requires PHP 8 with the `pdo_sqlite` extension (included in GitHub Codespaces PHP images).

```bash
php -S 0.0.0.0:8000
```

Then open `http://localhost:8000/card-list.php` (in Codespaces, open the forwarded port 8000).

Validation tests: `http://localhost:8000/tests/validation-tests.php`

## Rebuild the database

`database/rewards.db` is created from `database/schema.sql` and `database/seed.sql`. To recreate it from scratch:

```bash
php database/build.php
```

This produces 5 ACCOUNT rows and 20 GIFTCARD rows. Sample accounts all use the password `Password1!` (stored as a bcrypt hash; used in HW4).

## Database

| Table | Columns |
|---|---|
| `ACCOUNT` | `account_id` (PK, auto), `username`, `password_hash`, `full_name`, `email`, `role` (Customer/Admin), `points_balance` |
| `GIFTCARD` | `card_id` (PK, auto), `card_name`, `card_type` (Shopping/Dining/Travel/Entertainment), `card_value`, `points_required`, `card_image` |

## Project layout

```
card-list.php  card-details.php  card-add.php  card-update.php  card-delete.php
includes/   header.php  nav.php  footer.php  db.php  validation.php
database/   schema.sql  seed.sql  rewards.db  build.php
tests/      validation-tests.php
css/        styles.css
images/     card-shopping.svg  card-dining.svg  card-travel.svg  card-entertainment.svg
```

- Every page loads the shared `header.php`, `nav.php`, and `footer.php` with `require_once`.
- `db.php` holds the single PDO connection (`get_db()`); every query that uses form or URL values is a prepared statement.
- `validation.php` holds the reusable validation functions and the `h()` wrapper around `htmlspecialchars()` used for all output.
- Deleting requires a POST confirmation; a GET request to `card-delete.php` only shows the confirmation page.

## Use Case Traceability

| Use case | PHP page or pages | Database table | Test evidence |
|---|---|---|---|
| View a List of Gift Cards | `card-list.php` | `GIFTCARD` | Opened `card-list.php` and confirmed all 20 seeded cards appear with image, name, and points, and that each links to its details page. |
| View Details of One Gift Card | `card-details.php` | `GIFTCARD` | Opened `card-details.php?card_id=1` and confirmed all attributes display read-only, then confirmed invalid and nonexistent IDs show a safe error. |
| Add a Gift Card | `card-add.php` | `GIFTCARD` | Submitted valid data, confirmed the redirect to `card-list.php` and the new record (count 20 to 21), and confirmed invalid data is rejected with error messages. |
| Update a Gift Card | `card-update.php` | `GIFTCARD` | Loaded the prepopulated form, changed the name, type, value, and points, and confirmed the new values on `card-details.php`. |
| Delete a Gift Card | `card-delete.php` | `GIFTCARD` | Confirmed a GET request does not delete, then confirmed the POST confirmation removed the record and returned to `card-list.php` (count 21 to 20). |

See `test-report.md` for full test steps and results, and `ai-use.txt` for AI use.
