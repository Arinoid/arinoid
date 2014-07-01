<?php

use yii\db\Schema;

class m140701_070619_create_bookmark_table extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bookmark}}', [
            'id' => Schema::TYPE_PK,
            'uri_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'title' => Schema::TYPE_STRING . '(255)',
            'description' => Schema::TYPE_STRING . '(2048)',
            'thumbnail' => Schema::TYPE_STRING . '(255)',
            'favorite' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 0',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%bookmark}}');
    }
}
