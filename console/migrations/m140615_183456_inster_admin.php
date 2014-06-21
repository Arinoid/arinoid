<?php

class m140615_183456_inster_admin extends \yii\db\Migration
{
    public function up()
    {
        $this->insert('{{%user}}', [
            'id' => 1,
            'username' => 'admin',
            'password_hash' => '$2y$13$L/4IJYUVHmeiSz7pg9COh.6oTrhQR7.gr85hFRVZzS9ihNAmDzi8W'
        ]);

    }

    public function down()
    {
        $this->delete('{{%user}}', [
            'id' => 1
        ]);
    }
}
