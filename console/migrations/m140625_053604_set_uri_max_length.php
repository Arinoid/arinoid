<?php

use yii\db\Schema;

class m140625_053604_set_uri_max_length extends \yii\db\Migration
{
    public function up()
    {
        $this->alterColumn('{{%uri}}', 'uri', Schema::TYPE_STRING . '(2048) NOT NULL');
    }

    public function down()
    {
        $this->alterColumn('{{%uri}}', 'uri', Schema::TYPE_TEXT . ' NOT NULL');
    }
}
