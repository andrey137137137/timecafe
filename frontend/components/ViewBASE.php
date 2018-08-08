<?php
namespace frontend\components;


use frontend\modules\cafe\models\Cafe;
use frontend\modules\users\models\Users;
use frontend\modules\visitor\models\Visitor;
use Yii;
use yii\db\Query;
use yii\web\View;
use yii\helpers\Url;
use yii\twig\ViewRenderer;
use common\components\SdViewBASE;
use kartik\growl\Growl;

class ViewBASE extends View
{

  public $all_params = [];
  public $first_init = true;
  public $description;
  public $h1;
  public $meta_head;

  public $contentBD;

  public $user_id = false;
  public $user = [];

  public $cafe =[];

  private function create_flash($type, $flashe)
  {
    $title = false;
    $icon = false;
    if (is_array($flashe)) {
      if (isset($flashe['title'])) $title = trim($flashe['title'], '.');
      if (isset($flashe['icon'])) $no_show_page = $flashe['icon'];
      $txt = $flashe['message'];
    } else {
      $txt = $flashe;
    }

    if (mb_strlen($txt) < 5) {
      return '';
    }

    $type_list=[
      "info"=>Growl::TYPE_INFO,
      "success"=>Growl::TYPE_SUCCESS,
      "warning"=>Growl::TYPE_WARNING,
    ];

    $type=isset($type_list[$type])?$type_list[$type]:Growl::TYPE_MINIMALIST;

    return Growl::widget([
        'type' => $type,
        'icon' => $icon,
        'title' => $title,
        'showSeparator' => true,
        'body' => $txt
    ]);
  }

  public function getNotification(){
    $session = Yii::$app->session;
    if ($session->isActive){
      $session->close();
    }
    $session->open();
    $flashes = $session->allFlashes;
    if (count($flashes) == 0) {
      return '';
    }

    $html = '';
    $flashes = array_reverse($flashes);
    foreach ($flashes as $type => $flashe) {
      //Yii::$app->session->removeFlash($type);
      if (is_array($flashe)) {
        if (isset($flashe['title']) && isset($flashe['message'])) {
          $html .= $this->create_flash($type, $flashe);
        } else {
          foreach ($flashe as $txt) {
            $html .= $this->create_flash($type, $txt);
          }
        }
      } elseif (is_string($flashe)) {
        $html .= $this->create_flash($type, $flashe);
      }
    }

    return $html;
  }

  public function init_param()
  {
    $this->first_init = false;

    if (!Yii::$app->user->isGuest) {
      $this->user_id = Yii::$app->user->id;
      $user = Yii::$app->user->identity;
      $this->user = (array)$user->getIterator();

      $this->all_params['user'] = (array)$user->getIterator();
      $this->all_params['user_id'] = Yii::$app->user->id;

      $this->cafe= Cafe::find()->where(['id'=>Yii::$app->session->get('cafe_id',0)])->one();
    }

    $request = Yii::$app->request;
    if ($request->isAjax) {
      return;
    }
  }

  public function render($view, $params = [], $context = null)
  {
    //return parent::render($view, $params, $context); // TODO: Change the autogenerated stub
    if ($this->first_init) {
      $this->init_param();
    }

    $this->all_params = array_merge($this->all_params, $params);
    if ($this->all_params && isset($this->all_params['exception'])) {
      Yii::$app->params['exception'] = $this->all_params['exception'];
    };
    Yii::$app->params['all_params'] = $this->all_params;

    $tags = '';
    foreach (Yii::$app->view->metaTags as $meta) {
      $tags .= $meta;
    }
    preg_match_all('/<[\s]*meta[\s]*(name|property)="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $tags, $match);
    $tags = [];
    foreach ($match[2] as $k => $name) {
      $tags[$name] = $match[3][$k];
    }
    Yii::$app->view->metaTags = [];


    if(isset($this->all_params['title'])) {
      $this->title = $this->all_params['title'];
    }
    if(isset($this->all_params['description'])) {
      $this->description = $this->all_params['description'];
    }

    //d($this->title);
    //ddd($tags);
    $params = array_merge((array)$this, $this->all_params);
    unset($this->all_params['all_params']);
    return parent::render($view, $params, $context); // TODO: Change the autogenerated stub
  }

  public function afterRender($viewFile, $params, &$output)
  {
    if ($this->first_init) {
      $this->init_param();
    }

    if(substr(Yii::$app->request->pathInfo,0,3)=="gii")return;
    $this->all_params = array_merge($this->all_params, $params);
    Yii::$app->params['all_params'] = $this->all_params;
    unset($this->all_params['all_params']);

    parent::afterRender($viewFile, $params, $output); // TODO: Change the autogenerated stub
    //ddd($this->all_params);

    $notif=$this->getNotification();
    if(strpos($output,'</body>')===false){
      $output.=$notif;
    }else{
      $output=str_replace('</body>',$notif.'</body>',$output);
    }

    $output = Yii::$app->TwigString->render(
        $output,
        $this->all_params
    );
  }
}