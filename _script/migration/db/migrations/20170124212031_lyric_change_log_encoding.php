<?php

use Phinx\Migration\AbstractMigration;

class LyricChangeLogEncoding extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE `lyric_log` CHANGE `field` `field` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
        ");

        $this->query("
            ALTER TABLE `lyric_log` CHANGE `new_value` `new_value` VARCHAR(9999) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
        ");
    }
}
