<?php

namespace frontend\modules\tarifs\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\tarifs\models\Tarifs;
use kartik\daterange\DateRangeBehavior;

/**
 * TarifsSearch represents the model behind the search form of `frontend\modules\tarifs\models\Tarifs`.
 */
class TarifsSearch extends Tarifs
{
  public $cafe_id_from;
  public $cafe_id_to;
  public $min_sum_from;
  public $min_sum_to;
  public $max_sum_from;
  public $max_sum_to;
  public $first_hour_from;
  public $first_hour_to;
  public $next_hour_from;
  public $next_hour_to;
  public $start_visit_from;
  public $start_visit_to;

  private $slideParams=array (
  'cafe_id' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'min_sum' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 0.1,
  ),
  'max_sum' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 0.1,
  ),
  'first_hour' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 0.1,
  ),
  'next_hour' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 0.1,
  ),
  'start_visit' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 0.1,
  ),
);

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id', 'cafe_id', 'cafe_id_from', 'cafe_id_to'], 'integer'],
      [['min_sum', 'min_sum_from', 'min_sum_to', 'max_sum', 'max_sum_from', 'max_sum_to', 'first_hour', 'first_hour_from', 'first_hour_to', 'next_hour', 'next_hour_from', 'next_hour_to', 'start_visit', 'start_visit_from', 'start_visit_to'], 'number'],
      [['active'], 'safe'],
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
    $query = Tarifs::find();

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
             'cafe_id' => Yii::$app->cafe->id,
             'min_sum' => $this->min_sum,
             'max_sum' => $this->max_sum,
             'first_hour' => $this->first_hour,
             'next_hour' => $this->next_hour,
             'start_visit' => $this->start_visit,
        ]);

        $query->andFilterWhere(['like', '.active', $this->active]);

        $query->andFilterWhere(['>=', 'cafe_id', $this->cafe_id_from])
            ->andFilterWhere(['<=', 'cafe_id', $this->cafe_id_to])
            ->andFilterWhere(['>=', 'min_sum', $this->min_sum_from])
            ->andFilterWhere(['<=', 'min_sum', $this->min_sum_to])
            ->andFilterWhere(['>=', 'max_sum', $this->max_sum_from])
            ->andFilterWhere(['<=', 'max_sum', $this->max_sum_to])
            ->andFilterWhere(['>=', 'first_hour', $this->first_hour_from])
            ->andFilterWhere(['<=', 'first_hour', $this->first_hour_to])
            ->andFilterWhere(['>=', 'next_hour', $this->next_hour_from])
            ->andFilterWhere(['<=', 'next_hour', $this->next_hour_to])
            ->andFilterWhere(['>=', 'start_visit', $this->start_visit_from])
            ->andFilterWhere(['<=', 'start_visit', $this->start_visit_to]);

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
