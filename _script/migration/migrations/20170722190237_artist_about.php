<?php

use Phinx\Migration\AbstractMigration;

class ArtistAbout extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE
                `artist`
            ADD
                `about`
                VARCHAR(2048) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
                AFTER `forbidden`;
        ");
    }
}
