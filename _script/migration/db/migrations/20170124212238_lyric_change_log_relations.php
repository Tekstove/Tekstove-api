<?php

use Phinx\Migration\AbstractMigration;

class LyricChangeLogRelations extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE `lyric_log`
            ADD CONSTRAINT `ll.user_id`
            FOREIGN KEY (`user_id`)
            REFERENCES `user`(`id`)
            ON DELETE RESTRICT
            ON UPDATE RESTRICT;
        ");
    }
}
