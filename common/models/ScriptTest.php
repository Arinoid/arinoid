<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "script_test".
 *
 * @property integer $id
 * @property integer $try
 * @property integer $created_at
 */
class ScriptTest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script_test';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['try', 'created_at'], 'required'],
            [['try', 'created_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'try' => 'Try',
            'created_at' => 'Created At',
        ];
    }
}
