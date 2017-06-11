<?php

use Phinx\Migration\AbstractMigration;

class ChatCensore extends AbstractMigration
{
    public function up()
    {
        $this->query('
            ALTER TABLE 
                `chat`
            ADD `id_override` INT UNSIGNED NULL DEFAULT NULL AFTER `id`;
        ');
    }
}
