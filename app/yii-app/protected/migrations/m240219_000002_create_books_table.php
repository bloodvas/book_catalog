<?php

class m240219_000002_create_books_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('books', array(
            'id' => 'pk',
            'title' => 'varchar(255) NOT NULL',
            'year' => 'int NOT NULL',
            'description' => 'text',
            'isbn' => 'varchar(20) NOT NULL',
            'cover_image' => 'varchar(255)',
            'created_at' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
        ));
        
        $this->createIndex('idx_books_title', 'books', 'title');
        $this->createIndex('idx_books_year', 'books', 'year');
        $this->createIndex('idx_books_isbn', 'books', 'isbn', true);
    }

    public function down()
    {
        $this->dropTable('books');
    }
}
