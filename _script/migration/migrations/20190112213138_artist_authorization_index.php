<?php

use Phinx\Migration\AbstractMigration;

class ArtistAuthorizationIndex extends AbstractMigration
{
    public function up()
    {
        $this->query("
            CREATE INDEX `authorization_index`
            ON artist(authorization) 
        ");
    }

    public function down()
    {
        $this->query("
            DROP index authorization_index ON artist
        ");
    }
}
