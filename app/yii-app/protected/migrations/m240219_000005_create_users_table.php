<?php

class m240219_000005_create_users_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('users', array(
            'id' => 'pk',
            'username' => 'varchar(255) NOT NULL UNIQUE',
            'password' => 'varchar(255) NOT NULL',
            'created_at' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'timestamp DEFAULT CURRENT_TIMESTAMP',
        ));
        
        // Create default admin user
        $this->insert('users', array(
            'username' => 'admin',
            'password' => CPasswordHelper::hashPassword('admin123'),
        ));
    }

    public function down()
    {
        $this->dropTable('users');
    }
}
