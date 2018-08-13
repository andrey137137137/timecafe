<?php

namespace frontend\modules\cafe\controllers;

use Yii;
use frontend\modules\cafe\models\CafeParams;
use frontend\modules\cafe\models\CafeParamsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use johnitvn\ajaxcrud\BulkButtonWidget;

/**
 * AdminParamsController implements the CRUD actions for CafeParams model.
 */
class AdminParamsController extends Controller
{

    private $def_sel_column=[
          'id',
          'name',
          'vat_list',
          'time_zone',
      ];
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
      return [
        'verbs' => [
          'class' => VerbFilter::className(),
          'actions' => [
            'delete' => ['post'],
            'bulk-delete' => ['post'],
          ],
        ],
      ];
    }

    /**
     * Lists all CafeParams models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->can('CafeParamsView')) {
          throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
          return false;
        }

        $searchModel = new CafeParamsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
  
        $canCreate = Yii::$app->user->can('CafeParamsCreate');
        $actions = "";
        $actions.= Yii::$app->user->can('CafeParamsUpdate')?"{update}":"";

        $afterTable='';

        $columns = include(__DIR__.'/../views/adminParams/_columns.php');
        if(Yii::$app->user->isGuest){
          $sel_column=Yii::$app->session->get("columns_CafeParams",false);
        }else{
          $user=Yii::$app->getUser()->getIdentity();
          $sel_column=$user->getActiveColumn("columns_CafeParams");
        }
        if(!$sel_column){
          $sel_column=$this->def_sel_column;
        }
        foreach($columns as $k=>$column){
          $column_name=!is_array($column)?$column:(isset($column['attribute'])?$column['attribute']:false);
          if($column_name && !in_array($column_name,$sel_column)){
            unset($columns[$k]);
          }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns' => $columns,
            'canCreate' => $canCreate,
            'afterTable'=>$afterTable,
            'title'=>Yii::t('app', 'CafeParams list'),
            
            'forAllCafe'=>true,        ]);
    }


   /**
   * Config column in CafeParams model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionColumns()
  {
    if (Yii::$app->user->isGuest || !Yii::$app->user->can('CafeParamsView')) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }
    $model = new CafeParams();
    $searchModel = new CafeParamsSearch();

    $request = Yii::$app->request;

    if(!$request->isAjax){
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }

    Yii::$app->response->format = Response::FORMAT_JSON;

    if($request->post('column')){
      $col=$request->post('column');

      if(Yii::$app->user->isGuest){
        Yii::$app->session->set("columns_CafeParams",$col);
      }else{
        $user=Yii::$app->getUser()->getIdentity();
        $user->setActiveColumn("columns_CafeParams",$col);
      }

      return [
        'forceReload'=>'#crud-datatable-pjax',
       'content'=>Yii::$app->view->closeModal(),
      ];
    }
    $actions="";
    $columns = include(__DIR__.'/../views/adminParams/_columns.php');
    if(Yii::$app->user->isGuest){
      $sel_column=Yii::$app->session->get("columns_CafeParams",false);
    }else{
      $user=Yii::$app->getUser()->getIdentity();
      $sel_column=$user->getActiveColumn("columns_CafeParams");
    }
    if(!$sel_column){
      $sel_column=$this->def_sel_column;
    }
    foreach($columns as $k=>$column){
      $column_name=!is_array($column)?$column:(isset($column['attribute'])?$column['attribute']:false);
      if(!$column_name){
        unset($columns[$k]);
      }else{
        $columns[$k]=$column_name;
      }
    }

    return [
      'title'=> "Yii::t('app', 'Change visible columns in <?= CafeParams ?> table')",
      'content'=>$this->renderAjax('columns', [
        'sel_column' => $sel_column,
        'columns' => $columns,
        'model' => $model,
        'isAjax' => true
      ]),
      'footer'=> Html::button("Yii::t('app', 'Close')",['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
      Html::button("Yii::t('app', 'Save')",['class'=>'btn btn-primary','type'=>"submit"])

    ];
  }

    /**
     * Creates a new CafeParams model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
      if (Yii::$app->user->isGuest || !Yii::$app->user->can('CafeParamsCreate')) {
        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
        return false;
      }
      $request = Yii::$app->request;
      $model = new CafeParams();

      if(!$request->isAjax){
        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
        return false;
      }

      /*
      *   Process for ajax request
      */
      Yii::$app->response->format = Response::FORMAT_JSON;
      if($request->isGet){
        return [
          'title'=> "Yii::t('app', 'Create new CafeParams')",
          'content'=>$this->renderAjax('create', [
            'model' => $model,
            'isAjax' => true
          ]),
          'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
            Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        ];
      }else if($model->load($request->post()) && $model->save()){
        return [
          'forceReload'=>'#crud-datatable-pjax',
          'title'=> "Yii::t('app', 'Create new CafeParams')",
          'content'=>'<span class="text-success">Create CafeParams success</span>',
          'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

        ];
      }else{
        return [
          'title'=> "Yii::t('app', 'Create new CafeParams')",
          'content'=>$this->renderAjax('create', [
            'model' => $model,
            'isAjax' => true,
          ]),
          'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
              Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])

        ];
      }
    }

    /**
     * Updates an existing CafeParams model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
      if (Yii::$app->user->isGuest || !Yii::$app->user->can('CafeParamsUpdate')) {
        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
        return false;
      }

        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if(!$request->isAjax){
          throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
          return false;
        }
        /*
        *   Process for ajax request
        */
        $title=Yii::t('app', 'Update Cafe Params: ' . $model->name, [
    'nameAttribute' => '' . $model->name,
]);
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isGet){
          return [
            'title'=> $title,
            'content'=>$this->renderAjax('update', [
              'model' => $model,
              'isAjax' => true,
            ]),
            'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
              Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
          ];
        }else if($model->load($request->post()) && $model->save()){
          return [
            'forceReload'=>'#crud-datatable-pjax',
            'title'=> $title,
            'content'=>"<script>$('.modal-header .close').click()</script>",
            'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
              Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
          ];
        }else{
          return [
            'title'=> $title,
            'content'=>$this->renderAjax('update', [
              'model' => $model,
              'isAjax' => true,
            ]),
            'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
              Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
          ];
        }
    }



    /**
     * Finds the CafeParams model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CafeParams the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CafeParams::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Page does not exist'));
        }
    }
}
