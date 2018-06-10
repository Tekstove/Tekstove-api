<?php

use Phinx\Migration\AbstractMigration;

class AlbumYearNull extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE
                album
            MODIFY
                `year` int(6) unsigned NULL
        ");
    }
}
