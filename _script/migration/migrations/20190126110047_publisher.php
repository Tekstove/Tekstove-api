<?php

use Phinx\Migration\AbstractMigration;

class Publisher extends AbstractMigration
{
    public function up()
    {
        $this->query("
          CREATE TABLE
              publisher
          (
            id INT unsigned AUTO_INCREMENT NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            PRIMARY KEY(id)
          ) 
          DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci 
          ENGINE = InnoDB;
        ");

        $this->query("
            CREATE TABLE lyric_publisher 
            (
              lyric_id INT unsigned NOT NULL, 
              publisher_id INT unsigned NOT NULL, 
              INDEX lp_lyric_id (lyric_id), 
              INDEX lp_publisher_id (publisher_id), 
              PRIMARY KEY(lyric_id, publisher_id)
            )
            DEFAULT CHARACTER SET utf8mb4 
            COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
        ");

        $this->query("
          ALTER TABLE lyric_publisher 
          ADD CONSTRAINT FK_lyric_publisher_on_lyric_delete FOREIGN KEY (lyric_id) 
          REFERENCES lyric (id) 
          ON DELETE CASCADE;
        ");

        $this->query("
            ALTER TABLE lyric_publisher 
            ADD CONSTRAINT FK_lyric_publisher_on_publisher_delete FOREIGN KEY (publisher_id) 
            REFERENCES publisher (id) 
            ON DELETE RESTRICT;
        ");
    }

    public function down()
    {
        $this->query("ALTER TABLE lyric_publisher DROP FOREIGN KEY FK_lyric_publisher_on_lyric_delete");
        $this->query("ALTER TABLE lyric_publisher DROP FOREIGN KEY FK_lyric_publisher_on_publisher_delete");
        $this->table("publisher")->drop();
        $this->table("lyric_publisher")->drop();
    }
}
