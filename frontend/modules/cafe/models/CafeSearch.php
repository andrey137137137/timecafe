<?php

namespace frontend\modules\cafe\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\cafe\models\Cafe;

/**
 * CafeSearch represents the model behind the search form of `frontend\modules\cafe\models\Cafe`.
 */
class CafeSearch extends Cafe
{
  public $max_person_from;
  public $max_person_to;

  private $slideParams=array (
  'max_person' => 
  array (
    'min' => 0,
    'max' => 200,
    'step' => 1,
  )
);
  /**
   * {@inheritdoc}
   */
  public function rules()
  {
      return [
      [['id', 'max_person', 'max_person_from', 'max_person_to', 'last_task', 'franchisee','params_id'], 'integer'],
            [['name', 'address','currency'], 'trim'],
            [['name', 'address','currency'], 'safe']
    ];
  }

  /**
   * {@inheritdoc}
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
    $query = Cafe::find();

    // add conditions that should always apply here

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);

    $this->load($params);

    if (!$this->validate()) {
        // uncomment the following line if you do not want to return any records when validation fails
        // $query->where('0=1');
        return $dataProvider;
    }


    // grid filtering conditions
    $query->andFilterWhere([
             'id' => $this->id,
             'max_person' => $this->max_person,
             'last_task' => $this->last_task,
             'franchisee' => $this->franchisee,
             'currency' =>$this->currency,
        ]);

        $query->andFilterWhere(['like', '.name', $this->name])
            ->andFilterWhere(['like', '.address', $this->address]);

        if($this->max_person_from) {
          $query->andFilterWhere(['>=', 'max_person', $this->max_person_from])
              ->andFilterWhere(['<=', 'max_person', $this->max_person_to]);
        }
    return $dataProvider;
  }

  public function getSlideParams($name){
    $base=(isset($this->slideParams[$name])?$this->slideParams[$name]:[]);
    if(!isset($base['min']))$base['min']=isset($base['max'])?$base['max']-100:0;
    if(!isset($base['max']))$base['max']=$base['min']+100;
    if(!isset($base['step']))$base['step']=($base['max']-$base['min'])/100;

    return $base;
  }
}
