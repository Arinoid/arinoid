<?php

use yii\db\Schema;

class m140712_090836_drop_tables extends \yii\db\Migration
{
    public function up()
    {
        $this->dropTable('{{%uri}}');
        $this->dropTable('{{%bookmark}}');
    }

    public function down()
    {
        echo "m140712_090836_drop_tables cannot be reverted.\n";

        return false;
    }
}
