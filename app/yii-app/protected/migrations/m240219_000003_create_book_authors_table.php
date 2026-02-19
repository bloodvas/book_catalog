<?php

class m240219_000003_create_book_authors_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('book_authors', array(
            'book_id' => 'int NOT NULL',
            'author_id' => 'int NOT NULL',
            'PRIMARY KEY (book_id, author_id)',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        
        $this->addForeignKey('fk_book_authors_book', 'book_authors', 'book_id', 'books', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_book_authors_author', 'book_authors', 'author_id', 'authors', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_book_authors_book', 'book_authors');
        $this->dropForeignKey('fk_book_authors_author', 'book_authors');
        $this->dropTable('book_authors');
    }
}
