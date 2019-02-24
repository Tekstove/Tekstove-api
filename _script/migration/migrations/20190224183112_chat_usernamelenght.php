<?php

use Phinx\Migration\AbstractMigration;

class ChatUsernamelenght extends AbstractMigration
{
    public function up()
    {
        $this->query("ALTER TABLE chat_online MODIFY username varchar(40) NOT NULL");
    }

    public function down()
    {
        $this->query("ALTER TABLE chat_online MODIFY username varchar(32) NOT NULL");
    }
}
