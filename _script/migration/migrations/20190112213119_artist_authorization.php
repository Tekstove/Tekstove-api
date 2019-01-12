<?php

use Phinx\Migration\AbstractMigration;

class ArtistAuthorization extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE
              artist
            ADD COLUMN `authorization` TINYINT NOT NULL DEFAULT 0
        ");
    }

    public function down()
    {
        $this->table('artist')->removeColumn('authorization');
    }
}
