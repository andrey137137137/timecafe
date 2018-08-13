<?php

namespace frontend\modules\visits\controllers;

use frontend\modules\visits\models\StartVisit;
use frontend\modules\visits\models\VisitorLog;
use kartik\widgets\Typeahead;
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
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionStart()
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
          count($model->errors)==0 &&
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

          if($visit->save()){
            Yii::$app->session->addFlash('success', Yii::t('app', 'New visitor added to cafe'));
            return [
                'title' => "",
                'content' => Yii::$app->view->closeModal(),
                'footer' => ""
            ];
          }

          if(count($model->errors)==0 && $model->type == 1){
            $model->type=2;
          }
          Yii::$app->session->addFlash('error', Yii::t('app', 'Error added visitor to cafe'));
        }
      };

      if($model->type===false)$model->type= key($type_list);
      if(!$model->lg)$model->lg = Yii::$app->user->identity->lg;

      $js='<script>
        $("#startvisit-f_name").bind(\'typeahead:select\', userAA)
        .bind(\'typeahead:change\', userAA);
        $(\'.modal-body\').find(\'input,select\').not("[type=radio]").on(\'change\',userAA);
       </script>';

      return [
          'title' => Yii::t('app',"Start new visit"),
          'content' => $this->renderAjax('start', [
            'model'=>$model,
            'AJ_classname'=>Typeahead::classname(),
              'type_list' => $type_list
          ]).$js,
          'footer' => Html::button(Yii::t('app','Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
              Html::button(Yii::t('app','Start'), ['class' => 'btn btn-primary', 'type' => "submit"])

      ];
    }

  public function actionView($id)
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

    $model=VisitorLog::find()->where(['id'=>$id])->one();

    if(!$model){
      return [
          'title' => Yii::t('app',"View visit error"),
          'content' =>Yii::t('app',"Visit not found"),
          'footer' => Html::button(Yii::t('app','Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]),

    ];
    }

    /*return $this->renderAjax('view', [
        'model'=>$model,
        'cafe' => Yii::$app->cafe,
    ]);*/

    return [
        'title' => '<span class="fa fa-user antagon-color-main"></span>'.Yii::t('app',"Estimation visit"),
        'content' => $this->renderAjax('view', [
            'model'=>$model,
            'cafe' => Yii::$app->cafe,
            ]),
        'footer' =>
            Html::button(Yii::t('app','Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
            Html::button(Yii::t('app','Stop'), ['class' => 'btn btn-primary', 'type' => "submit"])

    ];
  }
}
