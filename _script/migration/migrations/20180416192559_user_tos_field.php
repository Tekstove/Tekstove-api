<?php

use Phinx\Migration\AbstractMigration;

class UserTosField extends AbstractMigration
{
    public function up()
    {
        $this->query(
            "
                ALTER TABLE user
                ADD terms_accepted DATETIME DEFAULT null
            "
        );
    }
}
