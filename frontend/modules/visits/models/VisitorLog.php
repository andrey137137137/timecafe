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
    static function typeList ($type)
    {
      $t=[
        Yii::t('app', "Anonymous"),
        Yii::t('app', "New user"),
        Yii::t('app', "Regular")
      ];
      if(!is_numeric($type))return $t;
      return(isset($t[$type])?$t[$type]:false);
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
            [['add_time', 'finish_time'], 'safe'],
            [['comment', 'notice', 'visit_cnt', 'pay_man', 'cnt_disk', 'certificate_number'], 'string'],
            [['cost', 'sum', 'tip', 'tps', 'tvq', 'certificate_val', 'sum_no_cert'], 'number'],
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
            'tip' => Yii::t('app', 'Tip'),
            'tps' => Yii::t('app', 'Tps'),
            'tvq' => Yii::t('app', 'Tvq'),
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
        ];
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
    $colors=[
        'bg-lima',
        'bg-lima',
        'bg-lima',
    ];
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
      $visitor['visit_cnt']=$visit->visit_cnt;
      $visitor['type_str']=VisitorLog::typeList($visit->type);
      $visitor['color']=$colors[$visit->type];
      $visitor['icon']=$icons[$visit->type];

      $out[]=$visitor;
    }

    return $out;
  }

}
