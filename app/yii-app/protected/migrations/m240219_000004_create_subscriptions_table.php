<?php

class m240219_000004_create_subscriptions_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('subscriptions', array(
            'id' => 'pk',
            'author_id' => 'int NOT NULL',
            'phone' => 'varchar(20) NOT NULL',
            'created_at' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
        
        $this->addForeignKey('fk_subscriptions_author', 'subscriptions', 'author_id', 'authors', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_subscriptions_phone_author', 'subscriptions', 'phone, author_id', true);
    }

    public function down()
    {
        $this->dropForeignKey('fk_subscriptions_author', 'subscriptions');
        $this->dropTable('subscriptions');
    }
}
