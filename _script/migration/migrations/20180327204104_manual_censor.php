<?php

use Phinx\Migration\AbstractMigration;

class ManualCensor extends AbstractMigration
{
    public function up()
    {
        $this->query(
            "
                ALTER TABLE
                    lyric
                ADD
                    manual_censor TINYINT NOT NULL DEFAULT 0
            "
        );
    }
}
