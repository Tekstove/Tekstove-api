<?php

use Phinx\Migration\AbstractMigration;

class PublisherFacebookPage extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE
              publisher
            ADD COLUMN `facebook_page_id` VARCHAR(255) NULL DEFAULT null
        ");
    }

    public function down()
    {
        $this->table('publisher')->removeColumn('facebook_page_id');
    }
}
