<?php

use Phinx\Migration\AbstractMigration;

class ArtistFacebook extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE
              artist
            ADD COLUMN `facebook_page_id` VARCHAR(255) NULL DEFAULT null
        ");
    }

    public function down()
    {
        $this->table('artist')->removeColumn('facebook_page_id');
    }
}
