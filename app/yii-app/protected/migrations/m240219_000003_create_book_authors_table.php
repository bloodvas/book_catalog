<?php

class m240219_000003_create_book_authors_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('book_authors', array(
            'book_id' => 'int NOT NULL',
            'author_id' => 'int NOT NULL',
            'PRIMARY KEY (book_id, author_id)',
        ));

        // Foreign key constraints will be handled at application level
    }

    public function down()
    {
        $this->dropTable('book_authors');
    }
}
