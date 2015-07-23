<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tags;
use yii\data\SqlDataProvider;

/**
 * tagssearch represents the model behind the search form about `common\models\tags`.
 */
class tagssearch extends Tags
{
    use AssetTagTrait;
    public $tags;
    public $entity_name;
    public $cnt_result;
    public $city_id;
    public $add_entity_id;
    public $add_entity_type;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            ['tags','required','on'=>'search'],
            [['description'], 'string'],
            [['created_by', 'updated_by', 'status', 'issystem'], 'integer'],
            [['tags','city_id','created_on', 'updated_on','created_by', 'updated_by'], 'safe'],
            [['name'], 'string', 'max' => 250],
            [['ip'], 'string', 'max' => 15],
            //[['name'], 'unique']
        ];
    }
    
    public function attributeLabels() {
        return (array_merge([['city_id'=>'City']],parent::attributeLabels()));
    }
    
    /**
     * @inheritdoc
     */
    public function fields() {
        return [
            //'Id',
            'name',
        ];
    }
    
    

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return array_merge(['search'=>['tags']],Model::scenarios());
        //return Model::scenarios();
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
        $query = Tags::find()->where('status!=0');  
        
        /*$query->andFilterWhere([
            'issystem' => 0,
        ]);*/
        $query->andWhere('issystem = '.Tags::TYPE_EDITOR.' or issystem='.Tags::TYPE_INTERNAL);

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

    public function searchEntity($params,$limit = 20)
    {
        $db = Yii::$app->getDb();
        $this->load($params);
        if(empty($this->tags)){
            return new SqlDataProvider(['sql'=>'select * from tc_tagmap where 1=0']);
        }
        $tag_ids = [];
        if(!empty($this->tags)){
            $tag_arr = explode(',',$this->tags);
            $TagModel = self::find()->orWhere(['name'=>$tag_arr])->all();
            foreach($TagModel as $t){
                $tag_ids[] = $t->Id;
            }
            
        }
        $whereIds = '1 = 1';
        if(!empty($tag_ids)){
            $whereIds = 'tagId in ('.implode(',', $tag_ids).')';
        }
        
        $tag_entity_sql = '
            select tagId,entityId,entityType,concat_ws("_",entityType,entityId) as unique_mix,updated_on,created_on 
            from tc_tagmap
            where '.$whereIds.' 
            group by unique_mix            
            order by updated_on desc, created_on desc
        ';
        $tagEntityCommand = $db->createCommand($tag_entity_sql);
        $taggedEntities  = $tagEntityCommand->queryAll();
        $entityIds = [];
        foreach($taggedEntities as $r){
            $entityIds[] = $r['unique_mix'];
        }
        
        //echo "<pre>";print_r($entityIds);echo"</pre>";die;
        $condition = '';
        if(!empty($entityIds)){
            $condition = ' where AllEntities.mixid in("'.implode('","', $entityIds).'")';
        }
        else{
            $condition = ' where 1=0';
        }
        
        $city_ids = [];
        if(empty($this->city_id)){
            $all_city = City::find()->where('status != 0')->orderBy('id')->all();
            foreach ($all_city as $c){
                $city_ids[] = $c->id;
            }
        }
        else{
            $city_ids = $this->city_id;
        }
        $city_id_str = implode(',', $city_ids);
        
        
        $select = 'AllEntities.mixid,AllEntities.entity_id,AllEntities.entity_type,AllEntities.entity_name,AllEntities.city_id,TblCity.name as city_name,AllEntities.updated_on,AllEntities.created_on';
        $sql = '
            select count(distinct mixid) as cnt_result 
            from 
                    (
                        (SELECT 
                                TblVenue.id as entity_id,'.EntityType::VENUE_ENTITY_ID.' as entity_type,TblVenue.name as entity_name,TblVenue.city_id as city_id,TblVenue.created_on,TblVenue.updated_on, CONCAT_WS("_","'.EntityType::VENUE_ENTITY_ID.'",TblVenue.id) as mixid
                        From tc_venues TblVenue 
                        WHERE TblVenue.city_id in('.$city_id_str.') and TblVenue.status != 0
                        )

                        UNION

                        (SELECT TblEvent.id AS entity_id,"'.EntityType::EVENT_ENTITY_ID.'" as entity_type, TblEvent.name AS entity_name,TempVenue.city_id AS city_id,TblEvent.created_on,TblEvent.updated_on, CONCAT_WS("_","'.EntityType::EVENT_ENTITY_ID.'",TblEvent.id) as mixid
                        FROM tc_events TblEvent
                                        INNER JOIN
                                        ( 
                                                SELECT TblEventMap.event_id, TblEventMap.venue_id, TblVenue.city_id
                                                FROM tc_events_venus TblEventMap
                                                INNER JOIN tc_venues TblVenue ON (TblVenue.id = TblEventMap.venue_id AND TblVenue.city_id IN ('.$city_id_str.') AND TblVenue.status != 0)
                                        ) AS TempVenue 
                                        ON (TempVenue.event_id = TblEvent.id)
                        WHERE TblEvent.status != 0)

                        UNION 

                        (SELECT TblContent.id AS entity_id,"'.EntityType::CONTENT_ENTITY_ID.'" as entity_type,TblContent.name AS entity_name,TblContentMap.city_id AS city_id, TblContent.created_on, TblContent.updated_on, CONCAT_WS("_","'.EntityType::CONTENT_ENTITY_ID.'",TblContent.id) as mixid
                        FROM tc_contents TblContent
                        INNER JOIN
                                tc_content_maps TblContentMap ON (TblContent.id = TblContentMap.content_id AND TblContentMap.city_id IN ('.$city_id_str.'))
                        WHERE TblContent.status != 0)
                    ) AS AllEntities 
                    left join tc_cities TblCity on(TblCity.id = AllEntities.city_id)
                    '.$condition.' 
        ';
        
        $count = self::findBySql($sql)->all();
        $sql = str_replace('count(distinct mixid) as cnt_result', $select, $sql);
        $sql .= ' group by mixid';
        
        
        if(empty($params['sort'])){
            $sql .= ' order by updated_on desc ';
        }
        //echo $sql;die;
        
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $count[0]->cnt_result,
            'sort' => [
                'attributes' => [
                    'entity_type',
                    'entity_id',
                    'entity_name',
                    'city_name',
                    'created_on',
                    'updated_on'=>[
                        'asc' => ['updated_on'=> SORT_ASC],
                        'desc' => ['updated_on' => SORT_DESC],
                        'default' => SORT_DESC,
                        'label' => 'Updated On',
                    ]
                ],
            ],
            'pagination' => [
                'pageSize' => $limit,
            ]
        ]);
        //$data = $dataProvider->getModels();
        //echo "<pre>";print_r($data);echo"</pre>";die;        
        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }
    
     
    
}
