<?php

use Phinx\Migration\AbstractMigration;

class ForumTipicLastActivity extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('forum_topic');

        if ($table->hasColumn('topic_posleden_post')) {

            $this->query("
                ALTER TABLE `forum_topic`
                CHANGE COLUMN 
                    `topic_posleden_post`
                    `last_activity` timestamp NOT NULL DEFAULT current_timestamp();
            ");
        } else {
            $this->query("
                ALTER TABLE forum_topic ADD last_activity timestamp NOT NULL DEFAULT current_timestamp();
            ");
        }
    }
}
