<?php

use Phinx\Migration\AbstractMigration;

class UserDelete extends AbstractMigration
{
    public function up()
    {
        $this->query(
            "
                ALTER TABLE
                    user
                ADD COLUMN
                    status TINYINT NOT NULL DEFAULT 0
            "
        );
    }
}
