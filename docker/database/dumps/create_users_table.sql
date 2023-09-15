CREATE TABLE users
(
    id            INT AUTO_INCREMENT NOT NULL,
    username      VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at    DATETIME DEFAULT NULL,
    updated_at    DATETIME DEFAULT NULL,
    UNIQUE INDEX username (username),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;
