<?php
namespace frontend\controllers;

use frontend\modules\visitor\models\Visitor;
use frontend\modules\visits\models\VisitorLog;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\modules\users\models\LoginForm;
use yii\web\Response;
use yii\helpers\Html;

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
      $cafe=($cafe_list[0]);
      Yii::$app->session->set('cafe_id',$cafe->cafe_id);
      return $this->goBack();
    }

    if(Yii::$app->request->isPost && Yii::$app->request->post('cafe')){
      $cafe_id=Yii::$app->request->post('cafe');
       foreach ($cafe_list as $cafe){
         if($cafe['id']==$cafe_id){
           Yii::$app->session->set('cafe_id',$cafe_id);
           if(Yii::$app->request->isAjax) {
             Yii::$app->response->format = Response::FORMAT_JSON;
             return [
                 'title' => Yii::t('app', 'Change cafe'),
                 'content' => Yii::$app->view->reloadPage(),
                 'footer' => ""
             ];
           }else {
             return $this->goBack();
           }
         }
       }
    }

    if(Yii::$app->request->isAjax) {
      Yii::$app->response->format = Response::FORMAT_JSON;
      return [
          'title' => Yii::t('app', 'Change cafe'),
          'content' => $this->renderAjax('change-cafe', [
            'cafe_list' => $cafe_list,
            'isModal'=>true,
            'cafe_change' =>Yii::$app->cafe->id,
          ]),
          'footer' => Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
              Html::button(Yii::t('app', 'Select'), ['class' => 'btn btn-primary', 'type' => "submit"])

      ];
    }
    Yii::$app->layout="form_page";

    return $this->render('change-cafe', [
        'cafe_list' => $cafe_list,
    ]);
  }

  public function actionGet_cafe_status(){
    $request = Yii::$app->request;
    if (Yii::$app->user->isGuest || !Yii::$app->cafe->id && !$request->isAjax) {
      throw new ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }

    $out=[
        'visitors'=> VisitorLog::getUserInCafe()
    ];

    Yii::$app->response->format = Response::FORMAT_JSON;
    return $out;
  }

  public function actionTpls(){
    $request = Yii::$app->request;
    if (Yii::$app->user->isGuest || !Yii::$app->cafe->id && !$request->isAjax && !$request->isGet) {
      throw new ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }

    $out=[];
    $path=Yii::$app->viewPath.'/browser/';
    $files=scandir($path);
    foreach ($files as $key => $value){
      if (!in_array($value,array(".",".."))){
        $name=str_replace('.twig','',$value);
        $out[$name]= file_get_contents ($path.$value);
      }
    }


    Yii::$app->response->format = Response::FORMAT_JSON;

    return $out;
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
