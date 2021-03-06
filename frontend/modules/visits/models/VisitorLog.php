<?php

namespace frontend\modules\visits\models;

use frontend\modules\cafe\models\Cafe;
use frontend\modules\users\models\Users;
use frontend\modules\visitor\models\Visitor;
use Yii;
use yii\web\User;

/**
 * This is the model class for table "visitor_log".
 *
 * @property int $id
 * @property int $user_id
 * @property int $visitor_id
 * @property int $type
 * @property int $cafe_id
 * @property string $add_time
 * @property string $comment
 * @property string $finish_time
 * @property double $cost
 * @property double $sum cost whithout vat
 * @property double $tip
 * @property double $tps
 * @property double $tvq
 * @property string $notice
 * @property int $pay_state
 * @property int $pause_start
 * @property int $pause
 * @property int $certificate_type
 * @property double $certificate_val
 * @property string $visit_cnt
 * @property string $pay_man
 * @property int $guest_m
 * @property int $guest_chi
 * @property string $cnt_disk
 * @property int $chi
 * @property double $sum_no_cert
 * @property int $pre_enter
 * @property int $kiosk_disc
 * @property int $terminal_ans
 * @property string $certificate_number
 */
class VisitorLog extends \yii\db\ActiveRecord
{
/*  public  $TypeList = [
      0 => Yii::t('app', "Anonymous"),
      1 => Yii::t('app', "New user"),
      2 => Yii::t('app', "Regular")
  ];
*/

    static $colors=[
      'bg-info',
      'bg-lima',
      'bg-regular',
    ];
    static $colors_payment=[
      -1=>'btn-danger',
      0=>'',
      1=>'bg-info',
      2=>'bg-tree-poppy',
    ];

    static function typeList ($type=false)
    {
      $type_list = [
          1 => Yii::t('app', "New user"),
          2 => Yii::t('app', "Regular")
      ];
      if (Yii::$app->cafe->can("AnonymousVisitor")) {
        $type_list[0] = Yii::t('app', "Anonymous");
      };
      ksort($type_list);

      if(!is_numeric($type))return $type_list;
      return(isset($type_list[$type])?$type_list[$type]:false);
    }

  static function payStatusList ($type=false)
  {
    $type_list = array();

    if(Yii::$app->cafe->can('payNOT')){
      $type_list[-1]=Yii::t('app', 'Not Paid');
    }
    $type_list[0]= '-';
    if(Yii::$app->cafe->can('payCash')){
      $type_list[1]=Yii::t('app', 'Cash');
    }
    if(Yii::$app->cafe->can('payCard')){
      $type_list[2]=Yii::t('app', 'Card');
    }
    if(!is_numeric($type))return $type_list;
    return(isset($type_list[$type])?$type_list[$type]:false);
  }
  /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visitor_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id','cafe_id'], 'required'],
            [['user_id', 'visitor_id', 'type', 'cafe_id', 'pay_state', 'pause_start', 'pause', 'certificate_type', 'guest_m', 'guest_chi', 'chi', 'pre_enter', 'kiosk_disc', 'terminal_ans'], 'integer'],
            [['add_time', 'finish_time','vat'], 'safe'],
            [['comment', 'notice', 'visit_cnt', 'pay_man', 'cnt_disk', 'certificate_number'], 'string'],
            [['cost', 'sum', 'certificate_val', 'sum_no_cert'], 'number'],
            ['comment','string','min'=>10]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'visitor_id' => Yii::t('app', 'Visitor ID'),
            'type' => Yii::t('app', 'Type'),
            'cafe_id' => Yii::t('app', 'Cafe ID'),
            'add_time' => Yii::t('app', 'Add Time'),
            'comment' => Yii::t('app', 'Comment'),
            'finish_time' => Yii::t('app', 'Finish Time'),
            'cost' => Yii::t('app', 'Cost'),
            'sum' => Yii::t('app', 'Sum'),
            'vat' => Yii::t('app', 'vat'),
            'notice' => Yii::t('app', 'Notice'),
            'pay_state' => Yii::t('app', 'Pay State'),
            'pause_start' => Yii::t('app', 'Pause Start'),
            'pause' => Yii::t('app', 'Pause'),
            'certificate_type' => Yii::t('app', 'Certificate Type'),
            'certificate_val' => Yii::t('app', 'Certificate Val'),
            'visit_cnt' => Yii::t('app', 'Visit Cnt'),
            'pay_man' => Yii::t('app', 'Pay Man'),
            'guest_m' => Yii::t('app', 'Guest M'),
            'guest_chi' => Yii::t('app', 'Guest Chi'),
            'cnt_disk' => Yii::t('app', 'Cnt Disk'),
            'chi' => Yii::t('app', 'Chi'),
            'sum_no_cert' => Yii::t('app', 'Sum No Cert'),
            'pre_enter' => Yii::t('app', 'Pre Enter'),
            'kiosk_disc' => Yii::t('app', 'Kiosk Disc'),
            'terminal_ans' => Yii::t('app', 'Terminal Ans'),
            'certificate_number' => Yii::t('app', 'Certificate Number'),
            'duration' => Yii::t('app', 'duration'),
            'status' => Yii::t('app', 'status'),
        ];
    }

    public function getDuration(){
      $duration=($this->finish_time?strtotime($this->finish_time):time());
      $duration-=strtotime($this->add_time);

      $duration-=$this->pause;


      if($this->pause_start>0){
        $duration-=(time()-$this->pause_start);
      }
      return $duration;
    }

    public function afterFind()
    {

      if(!$this->finish_time){
        $this->calcCost();
      }else{
        $this->vat=json_decode($this->vat,true);
      }

      if(!$this->user_id){
        $this->user_id = Yii::$app->user->id;
      }

      parent::afterFind(); // TODO: Change the autogenerated stub
    }

    public function calcCost(){
      if(count(Yii::$app->cafe->tariff)==0){
        $this->cost=false;
        $this->sum=Yii::t('app', 'No rates found for this cafe.');
        return;
      }

      if($this->visitor_id){
        $visits = explode("/",$this->visit_cnt);
        $visits = $visits[count($visits)-1];
      }else{
        $visits = 1;
      }
      foreach(Yii::$app->cafe->tariff as $tarif){
        if($visits>=$tarif['start_visit'])break;
      }
      if($visits<$tarif['start_visit']){
        $this->cost=false;
        $this->sum=Yii::t('app', 'No rates found for this cafe.');
        return;
      }

      $duration=$this->duration/3600;

      if($duration<1){
        $sum=$tarif['first_hour']*$duration;
      }else{
        $duration--;
        $sum=$tarif['first_hour']+$duration*$tarif['next_hour'];
      }
      if($sum<$tarif['min_sum']){
        $sum=$tarif['min_sum'];
      }else if($tarif['max_sum']>0 && $sum>$tarif['max_sum']){
        $sum=$tarif['max_sum'];
      };
      $sum=round($sum,2);

      //$this->sum=$sum;
      $vat=Yii::$app->cafe->params['vat_list'];
      if(!is_array($vat))$vat=json_decode($vat,true);

      $cost=$sum;
      $tot_vat=0;
      foreach($vat as $k=>$v){
        $p=$v['only_for_base_cost']?$sum:$cost;
        if($v['add_to_cost']){
          $vat[$k]['vat']=round($p*$v['value']/100,2);
          $cost+=$vat[$k]['vat'];
        }else{
          $vat[$k]['vat']=round($p/(1+$v['value']/100),2);
        }

        $tot_vat+=$vat[$k]['vat'];

        unset($v['only_for_base_cost']);
        unset($v['add_to_cost']);
      }

      $this->vat=$vat;
      $this->cost=round($cost,2);
      $this->sum=round($this->cost-$tot_vat,2);
    }
  /**
   * @return \yii\db\ActiveQuery
   */
  public function getCafe()
  {
    return $this->hasOne(Cafe::className(), ['id'=>'cafe_id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getVisitor()
  {
    return $this->hasOne(Visitor::className(), ['id' => 'visitor_id']);
  }

  /**
   * @return \yii\db\ActiveQuery
   */
  public function getUser()
  {
    return $this->hasOne(Users::className(), ['id'=>'user_id']);
  }

  public function beforeSave($insert)
  {
    if($this->visitor_id){
      $d30=VisitorLog::find()->andWhere(['visitor_id'=>$this->visitor_id])->andWhere(['>','add_time',date("Y-m-d H:i:s",time()-30*24*60*50)])->count();
      $d7=VisitorLog::find()->andWhere(['visitor_id'=>$this->visitor_id])->andWhere(['>','add_time',date("Y-m-d H:i:s",time()-7*24*60*50)])->count();
      $d=VisitorLog::find()->andWhere(['visitor_id'=>$this->visitor_id])->count();

      if($d==0 && $this->type==2){
        $this->type=1;
      }

      $d7++;
      $d30++;
      $d++;
      $this->visit_cnt=$d7.'/'.$d30.'/'.$d;
    }

    if(!$this->finish_time){
      unset($this->vat);
      unset($this->cost);
      unset($this->sum);
    }else if($this->vat && is_array($this->vat)){
      $this->vat=json_encode($this->vat);
    }

    return parent::beforeSave($insert); // TODO: Change the autogenerated stub
  }

  static function getUserInCafe($cafe_id=false){
    if(!$cafe_id){
      $cafe_id=Yii::$app->cafe->id;
    };

    $visitors = VisitorLog::find()
        ->andWhere(['cafe_id'=>$cafe_id])
        ->andWhere(['finish_time'=>null])
        ->all();
    $out=[];

    $icons=[
        'fa fa-user',
        'fa fa-user',
        'fa fa-user',
    ];
    foreach ($visitors as $visit){
      $visitor=array();
      if($visit->visitor_id){
        $v=$visit->visitor;
        $visitor['l_name']=$v['l_name'];
        $visitor['f_name']=$v['f_name'];
      }else{
        $visitor['f_name']=Yii::t('app', "Anonymous");
        $visitor['l_name']="";
      }
      $visitor['id']=$visit['id'];
      $visitor['start_time']=date(Yii::$app->params['lang']['time'],strtotime($visit['add_time']));
      $visitor['user']=$visit->user->name;
      $visitor['type']=$visit->type;
      $visitor['pause_start']=$visit->pause_start;
      $visitor['visit_cnt']=$visit->visit_cnt;
      $visitor['type_str']=VisitorLog::typeList($visit->type);
      $visitor['color']=VisitorLog::$colors[$visit->type];
      $visitor['icon']=$icons[$visit->type];

      $out[]=$visitor;
    }

    return $out;
  }

  public function endPause($onlyCalc=false){
    if(!$this->pause_start)return;
    $this->pause+=time()-$this->pause_start;
    if(!$onlyCalc) {
      $this->pause_start = 0;
    }
  }
}
