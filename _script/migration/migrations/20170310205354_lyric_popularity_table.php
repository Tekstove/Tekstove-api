<?php

use Phinx\Migration\AbstractMigration;

class LyricPopularityTable extends AbstractMigration
{
    public function change()
    {
        $this->query("
            CREATE TABLE `lyric_top_popularity`(
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
                `lyric_id` INT UNSIGNED NOT NULL ,
                `date` DATE NOT NULL ,
                `popularity` INT UNSIGNED NOT NULL ,
                PRIMARY KEY (`id`),
                INDEX `ltp_lyric` (`lyric_id`),
                INDEX `ltp_date` (`date`)
            ) ENGINE = InnoDB;
        ");

        $this->query("
            ALTER TABLE `lyric_top_popularity`
            ADD CONSTRAINT
                `ltp_constraint_lyric` FOREIGN KEY (`lyric_id`)
                REFERENCES `lyric`(`id`)
                ON DELETE RESTRICT
                ON UPDATE RESTRICT;
        ");
    }
}
