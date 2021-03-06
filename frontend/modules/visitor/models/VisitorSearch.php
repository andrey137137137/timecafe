<?php

namespace frontend\modules\visitor\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\visitor\models\Visitor;
use kartik\daterange\DateRangeBehavior;

/**
 * VisitorSearch represents the model behind the search form of `frontend\modules\visitor\models\Visitor`.
 */
class VisitorSearch extends Visitor
{
  public $create_from;
  public $create_to;


  public function behaviors()
  {
    return [
     [
       "class" => DateRangeBehavior::className(),
       "attribute" => "create",
       "dateStartAttribute" => "create_from",
       "dateEndAttribute" => "create_to",
      ],
    ];
  }
      /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id'], 'integer'],
      [['f_name', 'l_name', 'code', 'email', 'phone', 'create', 'notice', 'lg'], 'safe'],
      [['create'], 'match', 'pattern'=>'/^.+\s\-\s.+$/'],
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

  public function beforeValidate()
  {
    return true; // TODO: Change the autogenerated stub
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
    $query = Visitor::find();

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
        ]);

        $query->andFilterWhere(['like', '.f_name', $this->f_name])
            ->andFilterWhere(['like', '.l_name', $this->l_name])
            ->andFilterWhere(['like', '.code', $this->code])
            ->andFilterWhere(['like', '.email', $this->email])
            ->andFilterWhere(['like', '.phone', $this->phone])
            ->andFilterWhere(['like', '.notice', $this->notice])
            ->andFilterWhere(['like', '.lg', $this->lg]);

      if($this->create) {
        $query->andFilterWhere(['>=', 'create', date("Y-m-d H:i:s", $this->create_from)])
            ->andFilterWhere(['<=', 'create', date("Y-m-d H:i:s", $this->create_to + 86400)]);
      };
    return $dataProvider;
  }
}
