<?php

use Phinx\Migration\AbstractMigration;

class ArtistEdit extends AbstractMigration
{
    public function up()
    {
        $this->query("
            INSERT INTO `permission`(id, name, value)
            VALUES(19, 'artist.edit', 1)
        ");

        $this->query("
            INSERT INTO
                permission_group(id, name)
                VALUES(17, 'Администратор')
        ");

        $this->query("
            INSERT INTO
                permission_group_permission(group_id, permission_id)
                VALUES(17, 19)
        ");

        $this->query("
            INSERT INTO
                permission_group_user(group_id, user_id)
                VALUES(17, 54)
        ");
    }
}
