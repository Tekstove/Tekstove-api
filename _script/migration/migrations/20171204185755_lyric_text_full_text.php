<?php

use Phinx\Migration\AbstractMigration;

class LyricTextFullText extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE
                lyric
            ADD FULLTEXT INDEX lyric_text_ft_index (`text`)
        ");
    }
}
