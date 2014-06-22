<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "uri".
 *
 * @property integer $id
 * @property string $uri
 * @property string $uri_id
 * @property integer $created_at
 * @property integer $public
 */
class Uri extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uri';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uri', 'uri_id', 'created_at'], 'required'],
            [['created_at', 'public'], 'integer'],
            [['uri', 'uri_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uri' => 'Uri',
            'uri_id' => 'Uri ID',
            'created_at' => 'Created At',
            'public' => 'Public',
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Uri::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'public' => $this->public,
        ]);

        $query->andFilterWhere(['like', 'uri', $this->uri])
            ->andFilterWhere(['like', 'uri_id', $this->uri_id]);

        return $dataProvider;
    }
}
