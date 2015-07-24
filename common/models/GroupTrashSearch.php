<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Group;

/**
 * grouptrashsearch represents the model behind the search form about `app\models\group`.
 */
class GroupTrashSearch extends Group
{
    public $parentGroup;
    
    public function rules()
    {
        return [
            //[['Id'], 'integer'],
            [['name','parentGroup'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = group::find();
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->setSort([
                'defaultOrder' => [                    
                    'parentId' => SORT_ASC,
                    'name' => SORT_ASC
                ]
            ]);
        
        $query->andFilterWhere([
                group::tableName().'.status' => '0',
            ]);

        if (!($this->load($params) && $this->validate())) {
            $query->joinWith(['parent']);
            return $dataProvider;
        }

        /*$query->andFilterWhere([
            'Id' => $this->Id,
            'createdon' => $this->createdon,
        ]);//*/

        $query->andFilterWhere(['like', group::tableName().'.name', $this->name]);
            //->andFilterWhere(['like', 'parentId', $this->ParentGroup])
            //->andFilterWhere(['like', 'status', $this->status]);
        
        if($this->parentGroup!='' && strtolower($this->parentGroup)!='root'){
            $query->joinWith(['parent' => function ($q) {
                    $q->where('parent.name LIKE "%' . $this->parentGroup . '%" ');
                }]);
        }else if(strtolower($this->parentGroup)=='root'){
            $query->andFilterWhere([
                group::tableName().'.parentId' => 0,
            ]);
        }

        return $dataProvider;
    }
}
