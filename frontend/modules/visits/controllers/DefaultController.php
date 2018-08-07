<?php

namespace frontend\modules\visits\controllers;

use frontend\modules\visits\model\StartVisit;
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
      /*if (!$request->isAjax) {
        throw new ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
        return false;
      }*/
      Yii::$app->response->format = Response::FORMAT_JSON;

      $model= new StartVisit();

      $type_list=[
          1=>Yii::t('app',"New user"),
          2=>Yii::t('app',"Regular")
      ];
      if(Yii::$app->cafe->can("AnonymousVisitor")){
        $type_list[0]=Yii::t('app',"Anonymous");
      };
      ksort($type_list);

      if($model->load($request->post())&&$model->validate()){
        return [
            'title'=> "",
            'content'=>"<script>$('.modal-header .close').click()</script>",
            'footer'=> ""
        ];
      }

      if($model->type===false)$model->type= key($type_list);

      $js='<script>
        $("#startvisit-f_name").bind(\'typeahead:select\', userAA)
        .bind(\'typeahead:change\', userAA);
        $(\'.modal-body\').find(\'input,select\').on(\'change\',userAA);
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
}
