<?php

use Phinx\Migration\AbstractMigration;

class ManualCensore extends AbstractMigration
{
    public function up()
    {
        $this->query(
            "
                ALTER TABLE
                    lyric
                ADD
                    manual_censore TINYINT NOT NULL DEFAULT 0
            "
        );
    }
}
