
DROP TABLE IF EXISTS user ; CREATE TABLE user (idUser BIGINT  AUTO_INCREMENT NOT NULL, pseudo VARCHAR(32), password VARCHAR(40), PRIMARY KEY (idUser) ) ENGINE=InnoDB;  DROP TABLE IF EXISTS room ; CREATE TABLE room (idRoom BIGINT  AUTO_INCREMENT NOT NULL, state INT, name VARCHAR, port INT, user_iduser BIGINT, PRIMARY KEY (idRoom) ) ENGINE=InnoDB;  DROP TABLE IF EXISTS player ; CREATE TABLE player (idUser BIGINT  AUTO_INCREMENT NOT NULL, idRoom BIGINT NOT NULL, PRIMARY KEY (idUser,  idRoom) ) ENGINE=InnoDB;  ALTER TABLE room ADD CONSTRAINT FK_room_user_iduser FOREIGN KEY (user_iduser) REFERENCES user (idUser); ALTER TABLE player ADD CONSTRAINT FK_player_idUser FOREIGN KEY (idUser) REFERENCES user (idUser); ALTER TABLE player ADD CONSTRAINT FK_player_idRoom FOREIGN KEY (idRoom) REFERENCES room (idRoom); 