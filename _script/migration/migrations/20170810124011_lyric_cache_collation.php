<?php

use Phinx\Migration\AbstractMigration;

class LyricCacheCollation extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE `lyric`
            CHANGE `cache_title_full` `cache_title_full` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            CHANGE `cache_title_short` `cache_title_short` VARCHAR(280) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            CHANGE `text` `text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            CHANGE `text_bg` `text_bg` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
        ");
    }
}
