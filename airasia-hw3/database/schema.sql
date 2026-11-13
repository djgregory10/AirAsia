-- AirAsia Rewards Redemption System: database schema (SQLite)
-- Re-runnable: drops and recreates both tables.

PRAGMA foreign_keys = ON;

DROP TABLE IF EXISTS GIFTCARD;
DROP TABLE IF EXISTS ACCOUNT;

-- ACCOUNT: customers and administrators (used for login and redemption in HW4)
CREATE TABLE ACCOUNT (
    account_id     INTEGER PRIMARY KEY AUTOINCREMENT,
    username       TEXT    NOT NULL UNIQUE,
    password_hash  TEXT    NOT NULL,
    full_name      TEXT    NOT NULL,
    email          TEXT    NOT NULL UNIQUE,
    role           TEXT    NOT NULL CHECK (role IN ('Customer', 'Admin')),
    points_balance INTEGER NOT NULL DEFAULT 0 CHECK (points_balance >= 0)
);

-- GIFTCARD: rewards a customer can redeem with points
CREATE TABLE GIFTCARD (
    card_id         INTEGER PRIMARY KEY AUTOINCREMENT,  -- generated automatically
    card_name       TEXT    NOT NULL,
    card_type       TEXT    NOT NULL
                    CHECK (card_type IN ('Shopping', 'Dining', 'Travel', 'Entertainment')),
    card_value      REAL    NOT NULL CHECK (card_value > 0),
    points_required INTEGER NOT NULL CHECK (points_required > 0),
    card_image      TEXT    NOT NULL DEFAULT 'card-shopping.svg'
);
