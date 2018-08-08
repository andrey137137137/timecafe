<?php

namespace frontend\modules\visitor\controllers;

use Yii;
use frontend\modules\visitor\models\Visitor;
use frontend\modules\visitor\models\VisitorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use johnitvn\ajaxcrud\BulkButtonWidget;

/**
 * AdminController implements the CRUD actions for Visitor model.
 */
class AdminController extends Controller
{

    private $def_sel_column=[
          'id',
          'f_name',
          'l_name',
          'code',
          'email',
          'phone',
          'create',
          'notice',
          'lg',
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
     * Lists all Visitor models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->can('VisitorView')) {
          throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
          return false;
        }

        $searchModel = new VisitorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
  
        $canCreate = Yii::$app->user->can('VisitorCreate');
        $actions = "";
        $actions.= Yii::$app->user->can('VisitorUpdate')?"{update}":"";
        $actions.= Yii::$app->user->can('VisitorDelete')?"{delete}":"";
        $afterTable='';
        if( Yii::$app->user->can('VisitorDelete')){
          $afterTable=BulkButtonWidget::widget([
            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; '.Yii::t('app', 'Delete All'),
            ["bulk-delete"] ,
            [
              "class"=>"btn btn-danger btn-xs",
              'role'=>'modal-remote-bulk',
              'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
              'data-request-method'=>'post',
              'data-confirm-title'=>Yii::t('app', 'Are you sure?'),
              'data-confirm-message'=>Yii::t('app', 'Are you sure want to delete this item')            ]),
            ]).
            '<div class="clearfix"></div>';
        };
        $columns = include(__DIR__.'/../views/admin/_columns.php');
        if(Yii::$app->user->isGuest){
          $sel_column=Yii::$app->session->get("columns_Visitor",false);
        }else{
          $user=Yii::$app->getUser()->getIdentity();
          $sel_column=$user->getActiveColumn("columns_Visitor");
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
            'title'=>Yii::t('app',"Visitor"),
            'forAllCafe'=>true,
        ]);
    }


   /**
   * Config column in Visitor model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionColumns()
  {
    if (Yii::$app->user->isGuest || !Yii::$app->user->can('VisitorView')) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }
    $model = new Visitor();
    $searchModel = new VisitorSearch();

    $request = Yii::$app->request;

    if(!$request->isAjax){
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }

    Yii::$app->response->format = Response::FORMAT_JSON;

    if($request->post('column')){
      $col=$request->post('column');

      if(Yii::$app->user->isGuest){
        Yii::$app->session->set("columns_Visitor",$col);
      }else{
        $user=Yii::$app->getUser()->getIdentity();
        $user->setActiveColumn("columns_Visitor",$col);
      }

      return [
        'forceReload'=>'#crud-datatable-pjax',
       'content'=>Yii::$app->view->closeModal(),
      ];
    }
    $actions="";
    $columns = include(__DIR__.'/../views/admin/_columns.php');
    if(Yii::$app->user->isGuest){
      $sel_column=Yii::$app->session->get("columns_Visitor",false);
    }else{
      $user=Yii::$app->getUser()->getIdentity();
      $sel_column=$user->getActiveColumn("columns_Visitor");
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
      'title'=> Yii::t('app',"Change visible columns in Visitor table"),
      'content'=>$this->renderAjax('columns', [
        'sel_column' => $sel_column,
        'columns' => $columns,
        'model' => $model,
        'isAjax' => true
      ]),
      'footer'=> Html::button(Yii::t('app','Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
      Html::button(Yii::t('app','Save'),['class'=>'btn btn-primary','type'=>"submit"])

    ];
  }

    /**
     * Creates a new Visitor model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
      if (Yii::$app->user->isGuest || !Yii::$app->user->can('VisitorCreate')) {
        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
        return false;
      }
      $request = Yii::$app->request;
      $model = new Visitor();

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
          'title'=> Yii::t('app',"Create new Visitor"),
          'content'=>$this->renderAjax('create', [
            'model' => $model,
            'isAjax' => true
          ]),
          'footer'=> Html::button(Yii::t('app','Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
            Html::button(Yii::t('app','Save'),['class'=>'btn btn-primary','type'=>"submit"])
        ];
      }else if($model->load($request->post()) && $model->save()){
        return [
          'forceReload'=>'#crud-datatable-pjax',
          'title'=> Yii::t('app',"Create new Visitor"),
          'content'=>'<span class="text-success">'.Yii::t('app','Create Visitor success').'</span>',
          'footer'=> Html::button(Yii::t('app','Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
            Html::a(Yii::t('app','Create More'),['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

        ];
      }else{
        return [
          'title'=> Yii::t('app',"Create new Visitor"),
          'content'=>$this->renderAjax('create', [
            'model' => $model,
            'isAjax' => true,
          ]),
          'footer'=> Html::button(Yii::t('app','Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
              Html::button(Yii::t('app','Save'),['class'=>'btn btn-primary','type'=>"submit"])

        ];
      }
    }

    /**
     * Updates an existing Visitor model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
      if (Yii::$app->user->isGuest || !Yii::$app->user->can('VisitorUpdate')) {
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
      $title=Yii::t('app', 'Update Visitor: {f_name} {l_name}', $model->toArray());
      Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isGet){
          return [
            'title'=> $title,
            'content'=>$this->renderAjax('update', [
              'model' => $model,
              'isAjax' => true,
            ]),
            'footer'=> Html::button(Yii::t('app','Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
              Html::button(Yii::t('app','Save'),['class'=>'btn btn-primary','type'=>"submit"])
          ];
        }else if($model->load($request->post()) && $model->save()){
          return [
            'forceReload'=>'#crud-datatable-pjax',
            'title'=> $title,
            'content'=>Yii::$app->view->closeModal(),
            'footer'=> Html::button(Yii::t('app','Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
              Html::a(Yii::t('app','Edit'),['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
          ];
        }else{
          return [
            'title'=> $title,
            'content'=>$this->renderAjax('update', [
              'model' => $model,
              'isAjax' => true,
            ]),
            'footer'=> Html::button(Yii::t('app','Close'),['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
              Html::button(Yii::t('app','Save'),['class'=>'btn btn-primary','type'=>"submit"])
          ];
        }
    }

    /**
     * Delete an existing Visitor model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
      if (Yii::$app->user->isGuest || !Yii::$app->user->can('VisitorDelete')) {
        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
        return false;
      }
      $request = Yii::$app->request;
      $this->findModel($id)->delete();

      if($request->isAjax){
        /*
        *   Process for ajax request
        */
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
      }else{
        /*
        *   Process for non-ajax request
        */
        return $this->redirect(['index']);
      }
    }

     /**
     * Delete multiple existing Visitor model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
      if (Yii::$app->user->isGuest || !Yii::$app->user->can('VisitorDelete')) {
        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
        return false;
      }
      $request = Yii::$app->request;
      $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
      foreach ( $pks as $pk ) {
        $model = $this->findModel($pk);
        $model->delete();
      }

      if($request->isAjax){
        /*
        *   Process for ajax request
        */
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
      }else{
        /*
        *   Process for non-ajax request
        */
        return $this->redirect(['index']);
      }
       
    }

    /**
     * Finds the Visitor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Visitor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Visitor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Page does not exist'));
        }
    }
}
