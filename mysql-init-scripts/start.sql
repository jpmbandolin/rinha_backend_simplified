CREATE TABLE IF NOT EXISTS `people`
(
    `uuid`      VARCHAR(32)  NOT NULL,
    `nickname`  VARCHAR(32)  NOT NULL,
    `name`      VARCHAR(100) NOT NULL,
    `birthdate` VARCHAR(10)  NOT NULL,
    `stack`     LONGTEXT,
    PRIMARY KEY (`uuid`),
    UNIQUE (`nickname`)
);

SET GLOBAL max_connections = 10000;