<?php

use yii\db\Schema;

class m140712_090949_fill_tables extends \yii\db\Migration
{
    public function up()
    {
        $this->insert('{{%uri}}', [
            'id' => 1,
            'uri' => 'http://arinoid.com/index',
            'uri_id' => 'e9Mbz',
            'created_at' => '1403408797',
            'public' => 1,
        ]);
        $this->insert('{{%uri}}', [
            'id' => 2,
            'uri' => 'http://arinoid.com/site',
            'uri_id' => 'yJC2O',
            'created_at' => '1403408797',
            'public' => 1,
        ]);
        $this->insert('{{%uri}}', [
            'id' => 3,
            'uri' => 'http://arinoid.com/logout',
            'uri_id' => 'v9Edd',
            'created_at' => '1403408797',
            'public' => 1,
        ]);
        $this->insert('{{%uri}}', [
            'id' => 4,
            'uri' => 'http://arinoid.com/tags',
            'uri_id' => 'QSTy2',
            'created_at' => '1403408797',
            'public' => 1,
        ]);
        $this->insert('{{%uri}}', [
            'id' => 5,
            'uri' => 'http://arinoid.com/short',
            'uri_id' => 'psN-x',
            'created_at' => '1403408797',
            'public' => 1,
        ]);
        $this->insert('{{%uri}}', [
            'id' => 6,
            'uri' => 'http://arinoid.com/qrcode',
            'uri_id' => 'x3urV',
            'created_at' => '1403408797',
            'public' => 1,
        ]);

    }

    public function down()
    {
        $this->delete('{{%user}}', ['id' => 1]);
        $this->delete('{{%user}}', ['id' => 2]);
        $this->delete('{{%user}}', ['id' => 3]);
        $this->delete('{{%user}}', ['id' => 4]);
        $this->delete('{{%user}}', ['id' => 5]);
        $this->delete('{{%user}}', ['id' => 6]);
    }
}
