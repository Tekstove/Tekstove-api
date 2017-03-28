<?php

use Phinx\Migration\AbstractMigration;

class ChatMessageUsername extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE chat
            ADD COLUMN username varchar(255) NOT NULL
        ");
    }
}
