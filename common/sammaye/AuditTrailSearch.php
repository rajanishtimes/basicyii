<?php

namespace common\sammaye;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\sammaye\AuditTrail;

/**
 * AuditTrailSearch represents the model behind the search form about `common\sammaye\AuditTrail`.
 */
class AuditTrailSearch extends AuditTrail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['old_value', 'new_value', 'action', 'model', 'field', 'stamp', 'user_id', 'model_id'], 'safe'],
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
        $query = AuditTrail::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'stamp' => $this->stamp,
        ]);

        $query->andFilterWhere(['like', 'old_value', $this->old_value])
            ->andFilterWhere(['like', 'new_value', $this->new_value])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'field', $this->field])
            ->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'model_id', $this->model_id]);

        return $dataProvider;
    }
}
