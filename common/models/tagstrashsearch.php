<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tags;

/**
 * tagstrashsearch represents the model behind the search form about `common\models\tags`.
 */
class tagstrashsearch extends Tags
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'created_by', 'updated_by', 'status', 'issystem'], 'integer'],
            [['name', 'description', 'created_on', 'updated_on', 'ip'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = tags::find()->where('status=0');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'Id' => $this->Id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_on' => $this->created_on,
            'updated_on' => $this->updated_on,
            'status' => $this->status,
            'issystem' => $this->issystem,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
