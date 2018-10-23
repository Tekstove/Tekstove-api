<?php

use Phinx\Migration\AbstractMigration;

class LyricSendDate extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE
              `lyric`
            ADD COLUMN send_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ");
    }
}
