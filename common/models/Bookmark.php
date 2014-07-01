<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "bookmark".
 *
 * @property integer $id
 * @property integer $uri_id
 * @property integer $user_id
 * @property integer $created_at
 * @property string $title
 * @property string $description
 * @property string $thumbnail
 * @property integer $favorite
 */
class Bookmark extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bookmark';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uri_id', 'user_id', 'created_at'], 'required'],
            [['uri_id', 'user_id', 'created_at', 'favorite'], 'integer'],
            [['title', 'thumbnail'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 2048]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uri_id' => 'Uri ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'title' => 'Title',
            'description' => 'Description',
            'thumbnail' => 'Thumbnail',
            'favorite' => 'Favorite',
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Bookmark::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
