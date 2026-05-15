USE warmupdb;

-- Table containing the Medium flag
CREATE TABLE IF NOT EXISTS members_hidden (
    id INT AUTO_INCREMENT PRIMARY KEY,
    flag_part VARCHAR(100)
);

-- The flag the student needs to find via Time-Based SQLi
INSERT INTO members_hidden (flag_part) VALUES ('TeXSS{T1m3_Is_Th3_Ult1m4t3_K3y}');

-- Add a dummy user so the ID '1337' exists for testing
INSERT INTO users (id, username) VALUES (1337, 'admin_hidden_account');
