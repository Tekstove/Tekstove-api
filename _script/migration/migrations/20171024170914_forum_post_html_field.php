<?php

use Phinx\Migration\AbstractMigration;

class ForumPostHtmlField extends AbstractMigration
{
    public function up()
    {
        $this->query("
            ALTER TABLE forum_post
            ADD COLUMN `text_html` text COLLATE utf8_bin NOT NULL
            AFTER `text`
            
        ");
    }
}
