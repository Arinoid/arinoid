<?php

use yii\db\Schema;

class m140712_090918_create_uri_table extends \yii\db\Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%uri}}', [
            'id' => Schema::TYPE_PK,
            'uri' => Schema::TYPE_STRING . '(2048) NOT NULL',
            'uri_id' => Schema::TYPE_STRING . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'public' => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT TRUE',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%uri}}');
    }
}
