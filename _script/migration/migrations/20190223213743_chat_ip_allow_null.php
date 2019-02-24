<?php

use Phinx\Migration\AbstractMigration;

class ChatIpAllowNull extends AbstractMigration
{
    public function up()
    {
        $this->query("ALTER TABLE chat MODIFY ip varchar(50) NULL");
    }

    public function down()
    {
        $this->query("ALTER TABLE chat MODIFY ip varchar(50) NOT NULL");
    }
}
