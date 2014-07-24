<?php

use yii\db\Schema;

class m140724_103523_bookmark_change_thumbnail extends \yii\db\Migration
{
    public function up()
    {
        $this->alterColumn('{{%bookmark}}', 'thumbnail', Schema::TYPE_BINARY);
    }

    public function down()
    {
        $this->alterColumn('{{%bookmark}}', 'thumbnail', Schema::TYPE_STRING . '(255)');
    }
}
