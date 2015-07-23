<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\UserListType;

/**
 * UserListTypeSearch represents the model behind the search form about `common\models\UserListType`.
 */
class UserListTypeSearch extends UserListType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id', 'userType', 'userId', 'createdBy', 'updatedBy', 'status'], 'integer'],
            [['reason', 'createdOn', 'updatedOn', 'ip'], 'safe'],
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
        $query = UserListType::find()->where('status!=0');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'Id' => $this->Id,
            'userType' => $this->userType,
            'userId' => $this->userId,
            'createdOn' => $this->createdOn,
            'updatedOn' => $this->updatedOn,
            'createdBy' => $this->createdBy,
            'updatedBy' => $this->updatedBy,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
