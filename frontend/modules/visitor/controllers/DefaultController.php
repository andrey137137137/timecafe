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

    $results = [];


    $visitors=Visitor::findByString($term);

    if(!$visitors)return [];

    $visitors=$visitors->limit(30)->all();

    foreach($visitors as $model) {
      $data =$model->getAttributes();
      $results[] = $data;
    }

    return $results;
  }
}
