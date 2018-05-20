<?php

use Phinx\Migration\AbstractMigration;

class RemoveBirthday extends AbstractMigration
{
    public function up()
    {
        $this->query(
            "
                ALTER TABLE user
                DROP COLUMN birthday,
                DROP COLUMN skype
            "
        );
    }
}
