<?php

namespace frontend\modules\visits\controllers;

use frontend\modules\visits\models\StartVisit;
use frontend\modules\visits\models\VisitorLog;
use kartik\widgets\Typeahead;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Default controller for the `visits` module
 */
class DefaultController extends Controller
{

  public function beforeAction($action)
  {
    if (Yii::$app->user->isGuest || !Yii::$app->cafe->can("startVisit")) {
      throw new ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }

    $request = Yii::$app->request;
    if (!$request->isAjax) {
      throw new ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }
    Yii::$app->response->format = Response::FORMAT_JSON;

    return parent::beforeAction($action); // TODO: Change the autogenerated stub
  }

  public function actionStart()
  {
    $request = Yii::$app->request;
    $model = new StartVisit();

    $type_list = [
        1 => Yii::t('app', "New user"),
        2 => Yii::t('app', "Regular")
    ];
    if (Yii::$app->cafe->can("AnonymousVisitor")) {
      $type_list[0] = Yii::t('app', "Anonymous");
    };
    ksort($type_list);

    if ($model->load($request->post()) && $model->validate()) {
      if ($model->type == 2) {
        $model = StartVisit::find()->where(['id' => $model->id])->one();
        $model->load($request->post());
      }
      if ($model->type == 1) $model->id = null;

      if (
          count($model->errors) == 0 &&
          ($model->type == 0 || $model->save())
      ) {
        $visit = new VisitorLog();
        if ($model->type > 0) {
          $visit->visitor_id = $model->id;
        }
        $visit->type = $model->type;
        $visit->user_id = Yii::$app->user->id;
        $visit->cafe_id = Yii::$app->cafe->id;
        $visit->add_time = date("Y-m-d H:i:s");

        if ($visit->save()) {
          Yii::$app->session->addFlash('success', Yii::t('app', 'New visitor added to cafe'));
          return [
              'title' => "",
              'content' => Yii::$app->view->closeModal(),
              'footer' => ""
          ];
        }

        if (count($model->errors) == 0 && $model->type == 1) {
          $model->type = 2;
        }
        Yii::$app->session->addFlash('error', Yii::t('app', 'Error added visitor to cafe'));
      }
    };

    if ($model->type === false) $model->type = key($type_list);
    if (!$model->lg) $model->lg = Yii::$app->user->identity->lg;

    $js = '<script>
        $("#startvisit-f_name").bind(\'typeahead:select\', userAA)
        .bind(\'typeahead:change\', userAA);
        $(\'.modal-body\').find(\'input,select\').not("[type=radio]").on(\'change\',userAA);
       </script>';

    return [
        'title' => Yii::t('app', "Start new visit"),
        'content' => $this->renderAjax('start', [
                'model' => $model,
                'AJ_classname' => Typeahead::classname(),
                'type_list' => $type_list
            ]) . $js,
        'footer' => Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
            Html::button(Yii::t('app', 'Start'), ['class' => 'btn btn-primary', 'type' => "submit"])

    ];
  }

  public function actionView($id)
  {
    $model = VisitorLog::find()->where(['id' => $id])->one();

    if (!$model) {
      return [
          'title' => Yii::t('app', "View visit error"),
          'content' => Yii::t('app', "Visit not found"),
          'footer' => Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),

      ];
    }

    /*return $this->renderAjax('view', [
        'model'=>$model,
        'cafe' => Yii::$app->cafe,
    ]);*/

    $button = Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]);


    if ($model->cost !== false) {
      if($model->pause_start){
        $button .= Html::a(Yii::t('app', 'Resume'), ['/visits/pause', 'id' => $model->id,'resume'=>true], ['class' => 'btn bg-blue fg-white', "role" => "modal-remote"]);
      }else{
        $button .= Html::a(Yii::t('app', 'Pause'), ['/visits/pause', 'id' => $model->id], ['class' => 'btn bg-blue fg-white', "role" => "modal-remote"]);
      }

      $button .= Html::a(Yii::t('app', 'Stop'), ['/visits/stop', 'id' => $model->id], ['class' => 'btn btn-success', "role" => "modal-remote"]);
    }

    return [
        'title' => '<span class="fa fa-user antagon-color-main"></span>' . Yii::t('app', "Estimation visit"),
        'content' => $this->renderAjax('view', [
            'model' => $model,
            'cafe' => Yii::$app->cafe,
        ]),
        'footer' => $button
    ];
  }

  public function actionPause($id,$resume=false){
    $model = VisitorLog::find()->where(['id' => $id])->one();

    if (!$model) {
      return [
          'title' => Yii::t('app', "View visit error"),
          'content' => Yii::t('app', "Visit not found"),
          'footer' => Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),

      ];
    }

    if($model->finish_time){
      return [
          'title' => Yii::t('app', "View visit error"),
          'content' => Yii::t('app', "The visit has already been completed"),
          'footer' => Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
      ];
    }

    if($resume){
      if($model->pause_start==0){
        return [
            'title' => Yii::t('app', "View visit error"),
            'content' => Yii::t('app', "The visit is not on a pause"),
            'footer' => Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
        ];
      }
      $model->endPause();
    }else{
      if($model->pause_start>0){
        return [
            'title' => Yii::t('app', "View visit error"),
            'content' => Yii::t('app', "The visit is already on a pause"),
            'footer' => Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),
        ];
      }
      $model->pause_start=time();
    }
    $model->save();

    Yii::$app->session->addFlash('success', Yii::t('app', $resume?'The visit was removed from a pause.':'The visit is paused'));
    return [
        'title' => "",
        'content' => Yii::$app->view->closeModal(),
        'footer' => ""
    ];

  }

}
