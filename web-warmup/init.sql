CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(50),
    flag VARCHAR(255)
);

INSERT INTO users (username, password, flag) VALUES (
    'anonymous',
    'hahahahahahahayoucantguessthispassss12:74y1iu]][[;',
    'TeXSS{Head_For_The_Easy_Level_Now}'
);
