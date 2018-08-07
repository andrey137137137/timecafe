<?php

namespace frontend\modules\visitor\controllers;

use frontend\modules\visitor\models\Visitor;
use frontend\modules\visits\model\StartVisit;
use yii\web\Controller;
use Yii;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use \yii\web\ForbiddenHttpException;
/**
 * Default controller for the `visits` module
 */
class DefaultController extends Controller
{

  public function actionAjax($term){
    if (Yii::$app->user->isGuest || !Yii::$app->cafe->can("startVisit")) {
      throw new ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }

    $request = Yii::$app->request;
    /*if (!$request->isAjax) {
      throw new ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }*/
    Yii::$app->response->format = Response::FORMAT_JSON;

    $term=str_replace('  ',' ',trim(strip_tags($term)));
    $results = [];
    $q = addslashes($term);

    $visitors=Visitor::find();
    $q=explode(' ',$q);
    if(count($q)==1){
      $visitors=$visitors->orWhere(['like','email',$q[0].'%',false]);
      $visitors=$visitors->orWhere(['like','phone',$q[0]]);
      $visitors=$visitors->orWhere(['like','f_name',$q[0].'%',false]);
      $visitors=$visitors->orWhere(['like','l_name',$q[0].'%',false]);
    }else if(count($q)==2){
      $visitors=$visitors->orWhere([
          'and',
          ['like','l_name',$q[0].'%',false],
          ['like','f_name',$q[1].'%',false],
          ]);

      $visitors=$visitors->orWhere([
          'and',
          ['like','l_name',$q[1].'%',false],
          ['like','f_name',$q[0].'%',false],
      ]);
    }else{
      return array();
    }


      /*  ->orWhere(['like','f_name',$q])*/

    $visitors=$visitors->limit(30)->all();

    foreach($visitors as $model) {
      $data =$model->getAttributes();
      $results[] = $data;
    }

    return $results;
  }
}
