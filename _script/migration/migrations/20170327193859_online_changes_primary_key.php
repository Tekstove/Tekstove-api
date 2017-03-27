<?php

use Phinx\Migration\AbstractMigration;

class OnlineChangesPrimaryKey extends AbstractMigration
{
    public function up()
    {
        $this->query("ALTER TABLE chat_online DROP PRIMARY KEY");
        
        $this->query("ALTER TABLE `chat_online` CHANGE `user_id` `user_id` INT(11) UNSIGNED NULL DEFAULT NULL");
    }
}
