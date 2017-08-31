<?php

use Phinx\Migration\AbstractMigration;

class DropcacheTitleFull extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('lyric');
        if ($table->hasColumn('cache_title_full')) {
            $table->removeColumn('cache_title_full');
        }
    }
}
