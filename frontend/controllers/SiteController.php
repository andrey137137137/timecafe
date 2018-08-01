<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\modules\users\models\LoginForm;


/**
 * Site controller
 */
class SiteController extends Controller
{
  /**
   * {@inheritdoc}
   */
  public function behaviors()
  {
    return [
        'access' => [
            'class' => AccessControl::className(),
            'only' => ['logout', 'signup'],
            'rules' => [
                [
                    'actions' => ['signup'],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                [
                    'actions' => ['logout'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ],
        'verbs' => [
            'class' => VerbFilter::className(),
            'actions' => [
                'logout' => ['get'],
            ],
        ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function actions()
  {
    return [
        'error' => [
            'class' => 'yii\web\ErrorAction',
        ],
        'captcha' => [
            'class' => 'yii\captcha\CaptchaAction',
            'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
        ],
    ];
  }

  /**
   * Displays homepage.
   *
   * @return mixed
   */
  public function actionIndex()
  {
    return $this->render('index', [
        'page_wrap' => 'index',
        'screen_class' => 'start-screen',
    ]);
  }

  public function actionChangeCafe()
  {
    $cafe_list=Yii::$app->user->identity->cafes;

    if(count($cafe_list)==0){
      $cafe_list=Yii::$app->user->identity->getCafesList(Yii::$app->user->identity->franchisee);
    }

    if(count($cafe_list)==1){
      $cafe=(array)($cafe_list[0]);
      Yii::$app->session->set('cafe_id',$cafe['id']);
      return $this->goBack();
    }

    if(Yii::$app->request->isPost && Yii::$app->request->post('cafe')){
      $cafe_id=Yii::$app->request->post('cafe');
       foreach ($cafe_list as $cafe){
         if($cafe['id']==$cafe_id){
           Yii::$app->session->set('cafe_id',$cafe_id);
           return $this->goBack();
         }
       }
    }

    Yii::$app->layout="form_page";

    return $this->render('change-cafe', [
        'cafe_list' => $cafe_list,
    ]);
  }
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        Yii::$app->layout="form_page";

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
      Yii::$app->user->logout();

      return $this->goHome();
    }
}
