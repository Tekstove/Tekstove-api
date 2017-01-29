<?php

use Phinx\Migration\AbstractMigration;

class LyricChangeLog extends AbstractMigration
{
    public function up()
    {
        $this->query("
            CREATE TABLE `lyric_log`
            (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `lyric_id` INT UNSIGNED NOT NULL,
                `user_id` INT UNSIGNED NULL,
                `field` VARCHAR(255) NOT NULL,
                `new_value` VARCHAR(9999) NOT NULL,
                `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                INDEX `ll.lyric_id` (`lyric_id`),
                INDEX `ll.user_id` (`user_id`)
            ) ENGINE = InnoDB;
        ");
    }
}
