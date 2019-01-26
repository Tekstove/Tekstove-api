<?php

use Phinx\Migration\AbstractMigration;

class PublisherAuthrorization extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE
              publisher
            ADD COLUMN `authorization` TINYINT NOT NULL DEFAULT 0
        ");
    }

    public function down()
    {
        $this->table('publisher')->removeColumn('authorization');
    }
}
