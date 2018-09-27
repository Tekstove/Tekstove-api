<?php

use Phinx\Migration\AbstractMigration;

class AlbimImageLen extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE album
            CHANGE image image varchar(120) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
        ");
    }
}
