<?php

namespace frontend\modules\visits\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\visits\models\VisitorLog;
use kartik\daterange\DateRangeBehavior;

/**
 * VisitorLogSearch represents the model behind the search form of `frontend\modules\visits\models\VisitorLog`.
 */
class VisitorLogSearch extends VisitorLog
{
  public $user_id_from;
  public $user_id_to;
  public $visitor_id_from;
  public $visitor_id_to;
  public $type_from;
  public $type_to;
  public $cafe_id_from;
  public $cafe_id_to;
  public $add_time_from;
  public $add_time_to;
  public $finish_time_from;
  public $finish_time_to;
  public $cost_from;
  public $cost_to;
  public $sum_from;
  public $sum_to;
  public $tip_from;
  public $tip_to;
  public $tps_from;
  public $tps_to;
  public $tvq_from;
  public $tvq_to;
  public $pay_state_from;
  public $pay_state_to;
  public $pause_start_from;
  public $pause_start_to;
  public $pause_from;
  public $pause_to;
  public $certificate_type_from;
  public $certificate_type_to;
  public $certificate_val_from;
  public $certificate_val_to;
  public $guest_m_from;
  public $guest_m_to;
  public $guest_chi_from;
  public $guest_chi_to;
  public $chi_from;
  public $chi_to;
  public $sum_no_cert_from;
  public $sum_no_cert_to;
  public $pre_enter_from;
  public $pre_enter_to;
  public $kiosk_disc_from;
  public $kiosk_disc_to;
  public $terminal_ans_from;
  public $terminal_ans_to;

  private $slideParams=array (
  'user_id' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'visitor_id' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'type' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'cafe_id' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'cost' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 0.1,
  ),
  'sum' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 0.1,
  ),
  'tip' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 0.1,
  ),
  'tps' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 0.1,
  ),
  'tvq' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 0.1,
  ),
  'pay_state' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'pause_start' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'pause' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'certificate_type' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'certificate_val' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 0.1,
  ),
  'guest_m' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'guest_chi' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'chi' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'sum_no_cert' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 0.1,
  ),
  'pre_enter' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'kiosk_disc' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
  'terminal_ans' => 
  array (
    'min' => 0,
    'max' => 100,
    'step' => 1,
  ),
);

  public function behaviors()
  {
    return [
     [
       "class" => DateRangeBehavior::className(),
       "attribute" => "add_time",
       "dateStartAttribute" => "add_time_from",
       "dateEndAttribute" => "add_time_to",
      ],
     [
       "class" => DateRangeBehavior::className(),
       "attribute" => "finish_time",
       "dateStartAttribute" => "finish_time_from",
       "dateEndAttribute" => "finish_time_to",
      ],
    ];
  }
      /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['id', 'user_id', 'user_id_from', 'user_id_to', 'visitor_id', 'visitor_id_from', 'visitor_id_to', 'type', 'type_from', 'type_to', 'cafe_id', 'cafe_id_from', 'cafe_id_to', 'pay_state', 'pay_state_from', 'pay_state_to', 'pause_start', 'pause_start_from', 'pause_start_to', 'pause', 'pause_from', 'pause_to', 'certificate_type', 'certificate_type_from', 'certificate_type_to', 'guest_m', 'guest_m_from', 'guest_m_to', 'guest_chi', 'guest_chi_from', 'guest_chi_to', 'chi', 'chi_from', 'chi_to', 'pre_enter', 'pre_enter_from', 'pre_enter_to', 'kiosk_disc', 'kiosk_disc_from', 'kiosk_disc_to', 'terminal_ans', 'terminal_ans_from', 'terminal_ans_to'], 'integer'],
      [['add_time', 'comment', 'finish_time', 'notice', 'visit_cnt', 'pay_man', 'cnt_disk', 'certificate_number'], 'safe'],
      [['add_time', 'finish_time'], 'match', 'pattern'=>'/^.+\s\-\s.+$/'],
      [['cost', 'cost_from', 'cost_to', 'sum', 'sum_from', 'sum_to', 'tip', 'tip_from', 'tip_to', 'tps', 'tps_from', 'tps_to', 'tvq', 'tvq_from', 'tvq_to', 'certificate_val', 'certificate_val_from', 'certificate_val_to', 'sum_no_cert', 'sum_no_cert_from', 'sum_no_cert_to'], 'number'],
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
    $query = VisitorLog::find();

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
             'user_id' => $this->user_id,
             'visitor_id' => $this->visitor_id,
             'type' => $this->type,
             'cafe_id' => $this->cafe_id,
             'cost' => $this->cost,
             'sum' => $this->sum,
             'tip' => $this->tip,
             'tps' => $this->tps,
             'tvq' => $this->tvq,
             'pay_state' => $this->pay_state,
             'pause_start' => $this->pause_start,
             'pause' => $this->pause,
             'certificate_type' => $this->certificate_type,
             'certificate_val' => $this->certificate_val,
             'guest_m' => $this->guest_m,
             'guest_chi' => $this->guest_chi,
             'chi' => $this->chi,
             'sum_no_cert' => $this->sum_no_cert,
             'pre_enter' => $this->pre_enter,
             'kiosk_disc' => $this->kiosk_disc,
             'terminal_ans' => $this->terminal_ans,
        ]);

        $query->andFilterWhere(['like', '.comment', $this->comment])
            ->andFilterWhere(['like', '.notice', $this->notice])
            ->andFilterWhere(['like', '.visit_cnt', $this->visit_cnt])
            ->andFilterWhere(['like', '.pay_man', $this->pay_man])
            ->andFilterWhere(['like', '.cnt_disk', $this->cnt_disk])
            ->andFilterWhere(['like', '.certificate_number', $this->certificate_number]);

        $query->andFilterWhere(['>=', 'user_id', $this->user_id_from])
            ->andFilterWhere(['<=', 'user_id', $this->user_id_to])
            ->andFilterWhere(['>=', 'visitor_id', $this->visitor_id_from])
            ->andFilterWhere(['<=', 'visitor_id', $this->visitor_id_to])
            ->andFilterWhere(['>=', 'type', $this->type_from])
            ->andFilterWhere(['<=', 'type', $this->type_to])
            ->andFilterWhere(['>=', 'cafe_id', $this->cafe_id_from])
            ->andFilterWhere(['<=', 'cafe_id', $this->cafe_id_to])
            ->andFilterWhere(['>=', 'add_time', date("Y-m-d H:i:s",$this->add_time_from)])
            ->andFilterWhere(['<=', 'add_time', date("Y-m-d H:i:s",$this->add_time_to+86400)])
            ->andFilterWhere(['>=', 'finish_time', date("Y-m-d H:i:s",$this->finish_time_from)])
            ->andFilterWhere(['<=', 'finish_time', date("Y-m-d H:i:s",$this->finish_time_to+86400)])
            ->andFilterWhere(['>=', 'cost', $this->cost_from])
            ->andFilterWhere(['<=', 'cost', $this->cost_to])
            ->andFilterWhere(['>=', 'sum', $this->sum_from])
            ->andFilterWhere(['<=', 'sum', $this->sum_to])
            ->andFilterWhere(['>=', 'tip', $this->tip_from])
            ->andFilterWhere(['<=', 'tip', $this->tip_to])
            ->andFilterWhere(['>=', 'tps', $this->tps_from])
            ->andFilterWhere(['<=', 'tps', $this->tps_to])
            ->andFilterWhere(['>=', 'tvq', $this->tvq_from])
            ->andFilterWhere(['<=', 'tvq', $this->tvq_to])
            ->andFilterWhere(['>=', 'pay_state', $this->pay_state_from])
            ->andFilterWhere(['<=', 'pay_state', $this->pay_state_to])
            ->andFilterWhere(['>=', 'pause_start', $this->pause_start_from])
            ->andFilterWhere(['<=', 'pause_start', $this->pause_start_to])
            ->andFilterWhere(['>=', 'pause', $this->pause_from])
            ->andFilterWhere(['<=', 'pause', $this->pause_to])
            ->andFilterWhere(['>=', 'certificate_type', $this->certificate_type_from])
            ->andFilterWhere(['<=', 'certificate_type', $this->certificate_type_to])
            ->andFilterWhere(['>=', 'certificate_val', $this->certificate_val_from])
            ->andFilterWhere(['<=', 'certificate_val', $this->certificate_val_to])
            ->andFilterWhere(['>=', 'guest_m', $this->guest_m_from])
            ->andFilterWhere(['<=', 'guest_m', $this->guest_m_to])
            ->andFilterWhere(['>=', 'guest_chi', $this->guest_chi_from])
            ->andFilterWhere(['<=', 'guest_chi', $this->guest_chi_to])
            ->andFilterWhere(['>=', 'chi', $this->chi_from])
            ->andFilterWhere(['<=', 'chi', $this->chi_to])
            ->andFilterWhere(['>=', 'sum_no_cert', $this->sum_no_cert_from])
            ->andFilterWhere(['<=', 'sum_no_cert', $this->sum_no_cert_to])
            ->andFilterWhere(['>=', 'pre_enter', $this->pre_enter_from])
            ->andFilterWhere(['<=', 'pre_enter', $this->pre_enter_to])
            ->andFilterWhere(['>=', 'kiosk_disc', $this->kiosk_disc_from])
            ->andFilterWhere(['<=', 'kiosk_disc', $this->kiosk_disc_to])
            ->andFilterWhere(['>=', 'terminal_ans', $this->terminal_ans_from])
            ->andFilterWhere(['<=', 'terminal_ans', $this->terminal_ans_to]);

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
