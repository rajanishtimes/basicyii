<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Permission;

/**
 * UserPermissionSearch represents the model behind the search form about `common\models\Permission`.
 */
class UserPermissionSearch extends Permission
{
    public function rules()
    {
        return [
            [['Id', 'userId', 'groupId', 'createdBy', 'updatedBy', 'status'], 'integer'],
            [['module', 'controller', 'action', 'createdOn', 'updatedOn'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Permission::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'Id' => $this->Id,
            'userId' => $this->userId,
            'groupId' => $this->groupId,
            'createdOn' => $this->createdOn,
            'createdBy' => $this->createdBy,
            'updatedOn' => $this->updatedOn,
            'updatedBy' => $this->updatedBy,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'module', $this->module])
            ->andFilterWhere(['like', 'controller', $this->controller])
            ->andFilterWhere(['like', 'action', $this->action]);

        return $dataProvider;
    }
}
