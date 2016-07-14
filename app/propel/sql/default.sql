
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(100),
    `password` VARCHAR(255) NOT NULL,
    `api_key` VARCHAR(255) NOT NULL,
    `mail` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(255),
    `about` VARCHAR(255),
    `autoplay` SMALLINT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- permission
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `permission`;

CREATE TABLE `permission`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `value` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- permission_group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `permission_group`;

CREATE TABLE `permission_group`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `image` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- permission_group_permission
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `permission_group_permission`;

CREATE TABLE `permission_group_permission`
(
    `group_id` INTEGER NOT NULL,
    `permission_id` INTEGER NOT NULL,
    PRIMARY KEY (`group_id`,`permission_id`),
    INDEX `permission_group_permission_fi_2b894c` (`permission_id`),
    CONSTRAINT `permission_group_permission_fk_a78f71`
        FOREIGN KEY (`group_id`)
        REFERENCES `permission_group` (`id`),
    CONSTRAINT `permission_group_permission_fk_2b894c`
        FOREIGN KEY (`permission_id`)
        REFERENCES `permission` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- permission_group_user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `permission_group_user`;

CREATE TABLE `permission_group_user`
(
    `user_id` INTEGER NOT NULL,
    `group_id` INTEGER NOT NULL,
    PRIMARY KEY (`user_id`,`group_id`),
    INDEX `permission_group_user_fi_a78f71` (`group_id`),
    CONSTRAINT `permission_group_user_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`),
    CONSTRAINT `permission_group_user_fk_a78f71`
        FOREIGN KEY (`group_id`)
        REFERENCES `permission_group` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- lyric
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `lyric`;

CREATE TABLE `lyric`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255),
    `text` VARCHAR(255),
    `text_bg` VARCHAR(255),
    `text_bg_added` DATETIME,
    `extra_info` VARCHAR(255),
    `send_by` INTEGER,
    `cache_title_short` VARCHAR(255),
    `views` INTEGER,
    `popularity` INTEGER,
    `votes_count` INTEGER,
    `video_youtube` VARCHAR(255),
    `video_vbox7` VARCHAR(255),
    `video_metacafe` VARCHAR(255),
    `download` VARCHAR(255),
    PRIMARY KEY (`id`),
    INDEX `lyric_fi_628e89` (`send_by`),
    CONSTRAINT `lyric_fk_628e89`
        FOREIGN KEY (`send_by`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- artist_lyric
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `artist_lyric`;

CREATE TABLE `artist_lyric`
(
    `lyric_id` INTEGER NOT NULL,
    `artist_id` INTEGER NOT NULL,
    `order` INTEGER,
    PRIMARY KEY (`lyric_id`,`artist_id`),
    INDEX `artist_lyric_fi_6fe112` (`artist_id`),
    CONSTRAINT `artist_lyric_fk_caf60d`
        FOREIGN KEY (`lyric_id`)
        REFERENCES `lyric` (`id`),
    CONSTRAINT `artist_lyric_fk_6fe112`
        FOREIGN KEY (`artist_id`)
        REFERENCES `artist` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- lyric_language
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `lyric_language`;

CREATE TABLE `lyric_language`
(
    `lyric_id` INTEGER NOT NULL,
    `language_id` INTEGER NOT NULL,
    PRIMARY KEY (`lyric_id`,`language_id`),
    INDEX `lyric_language_fi_5d937a` (`language_id`),
    CONSTRAINT `lyric_language_fk_caf60d`
        FOREIGN KEY (`lyric_id`)
        REFERENCES `lyric` (`id`),
    CONSTRAINT `lyric_language_fk_5d937a`
        FOREIGN KEY (`language_id`)
        REFERENCES `language` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- lyric_translation
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `lyric_translation`;

CREATE TABLE `lyric_translation`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `lyric_id` INTEGER,
    `user_id` INTEGER,
    `text` VARCHAR(255),
    PRIMARY KEY (`id`),
    INDEX `lyric_translation_fi_caf60d` (`lyric_id`),
    INDEX `lyric_translation_fi_29554a` (`user_id`),
    CONSTRAINT `lyric_translation_fk_caf60d`
        FOREIGN KEY (`lyric_id`)
        REFERENCES `lyric` (`id`),
    CONSTRAINT `lyric_translation_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- language
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `language`;

CREATE TABLE `language`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- lyric_vote
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `lyric_vote`;

CREATE TABLE `lyric_vote`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `lyric_id` INTEGER,
    `user_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `lyric_vote_fi_29554a` (`user_id`),
    INDEX `lyric_vote_fi_caf60d` (`lyric_id`),
    CONSTRAINT `lyric_vote_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`),
    CONSTRAINT `lyric_vote_fk_caf60d`
        FOREIGN KEY (`lyric_id`)
        REFERENCES `lyric` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- artist
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `artist`;

CREATE TABLE `artist`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `user_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `artist_fi_29554a` (`user_id`),
    CONSTRAINT `artist_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- album
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `album`;

CREATE TABLE `album`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `year` INTEGER,
    `image` VARCHAR(255),
    `user_id` INTEGER,
    PRIMARY KEY (`id`),
    INDEX `album_fi_29554a` (`user_id`),
    CONSTRAINT `album_fk_29554a`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
