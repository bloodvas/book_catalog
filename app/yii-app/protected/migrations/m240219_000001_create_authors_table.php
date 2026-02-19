<?php

class m240219_000001_create_authors_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('authors', array(
            'id' => 'pk',
            'full_name' => 'varchar(255) NOT NULL',
            'created_at' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        
        $this->createIndex('idx_authors_full_name', 'authors', 'full_name');
    }

    public function down()
    {
        $this->dropTable('authors');
    }
}
