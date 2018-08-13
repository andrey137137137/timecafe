<?php

namespace frontend\modules\cafe\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\cafe\models\CafeParams;
use kartik\daterange\DateRangeBehavior;
use yii\db\Expression;
use frontend\modules\visitor\models\Visitor;

/**
 * CafeParamsSearch represents the model behind the search form of `frontend\modules\cafe\models\CafeParams`.
 */
class CafeParamsSearch extends CafeParams
{

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id'], 'integer'],
      [['name',  'time_zone'], 'trim'],
      [['name', 'time_zone'], 'safe'],
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
    $query = CafeParams::find();

    // add conditions that should always apply here

    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 50,
      ],
      'sort'=>array(
        'defaultOrder'=>[
          'id'=>SORT_DESC
        ]
      ),
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
        'time_zone' =>$this->time_zone,
        ]);

        $query->andFilterWhere(['like', '.name', $this->name]);

    return $dataProvider;
  }
}
