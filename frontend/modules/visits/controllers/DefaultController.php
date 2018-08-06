<?php

namespace frontend\modules\visits\controllers;

use yii\web\Controller;
use Yii;

/**
 * Default controller for the `visits` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionCreate()
    {
      if (Yii::$app->user->isGuest || !Yii::$app->cafe->can("startVisit")) {
        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
        return false;
      }

        return $this->render('index');
    }
}
