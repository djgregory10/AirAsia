-- AirAsia Rewards Redemption System: sample data
-- All sample accounts use the password: Password1!

INSERT INTO ACCOUNT (username, password_hash, full_name, email, role, points_balance) VALUES
('admin',   '$2y$10$496tc6sZLxc/qVDk8Rq1yeVU/HiHaIKPOB8Yct/M9QjXMcpm7og1u', 'Amira Hassan',  'admin@airasia-demo.com',  'Admin',    0),
('jlim',    '$2y$10$496tc6sZLxc/qVDk8Rq1yeVU/HiHaIKPOB8Yct/M9QjXMcpm7og1u', 'Jun Lim',       'jlim@example.com',        'Customer', 12500),
('snair',   '$2y$10$496tc6sZLxc/qVDk8Rq1yeVU/HiHaIKPOB8Yct/M9QjXMcpm7og1u', 'Siti Nair',     'snair@example.com',       'Customer', 4800),
('rtan',    '$2y$10$496tc6sZLxc/qVDk8Rq1yeVU/HiHaIKPOB8Yct/M9QjXMcpm7og1u', 'Raj Tan',       'rtan@example.com',        'Customer', 26000),
('mwong',   '$2y$10$496tc6sZLxc/qVDk8Rq1yeVU/HiHaIKPOB8Yct/M9QjXMcpm7og1u', 'Mei Wong',      'mwong@example.com',       'Customer', 900);

INSERT INTO GIFTCARD (card_name, card_type, card_value, points_required, card_image) VALUES
('Amazon Gift Card',          'Shopping',      25.00,   2500, 'card-shopping.svg'),
('Walmart Gift Card',         'Shopping',      50.00,   5000, 'card-shopping.svg'),
('Target Gift Card',          'Shopping',      25.00,   2500, 'card-shopping.svg'),
('Best Buy Gift Card',        'Shopping',     100.00,  10000, 'card-shopping.svg'),
('IKEA Gift Card',            'Shopping',      50.00,   5000, 'card-shopping.svg'),
('Starbucks Gift Card',       'Dining',        10.00,   1000, 'card-dining.svg'),
('Chipotle Gift Card',        'Dining',        20.00,   2000, 'card-dining.svg'),
('Domino''s Gift Card',       'Dining',        25.00,   2500, 'card-dining.svg'),
('Olive Garden Gift Card',    'Dining',        50.00,   5000, 'card-dining.svg'),
('Uber Eats Gift Card',       'Dining',        30.00,   3000, 'card-dining.svg'),
('Airbnb Gift Card',          'Travel',       100.00,  10000, 'card-travel.svg'),
('Uber Rides Gift Card',      'Travel',        25.00,   2500, 'card-travel.svg'),
('Lyft Gift Card',            'Travel',        25.00,   2500, 'card-travel.svg'),
('Hilton Gift Card',          'Travel',       200.00,  20000, 'card-travel.svg'),
('Expedia Gift Card',         'Travel',       150.00,  15000, 'card-travel.svg'),
('Netflix Gift Card',         'Entertainment', 30.00,   3000, 'card-entertainment.svg'),
('Spotify Gift Card',         'Entertainment', 10.00,   1000, 'card-entertainment.svg'),
('Steam Gift Card',           'Entertainment', 20.00,   2000, 'card-entertainment.svg'),
('Fandango Gift Card',        'Entertainment', 25.00,   2500, 'card-entertainment.svg'),
('PlayStation Store Gift Card','Entertainment',50.00,   5000, 'card-entertainment.svg');
