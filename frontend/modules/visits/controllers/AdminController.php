<?php

namespace frontend\modules\visits\controllers;

use Yii;
use frontend\modules\visits\models\VisitorLog;
use frontend\modules\visits\models\VisitorLogSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use johnitvn\ajaxcrud\BulkButtonWidget;

/**
 * AdminController implements the CRUD actions for VisitorLog model.
 */
class AdminController extends Controller
{

    private $def_sel_column=[
          'id',
          'user_id',
          'visitor_id',
          'type',
          'cafe_id',
          'add_time',
          'comment',
          'finish_time',
          'cost',
          'sum',
          'notice',
          'pay_state',
          'pause_start',
          'pause',
          'certificate_type',
          'certificate_val',
          'visit_cnt',
          'pay_man',
          'guest_m',
          'guest_chi',
          'cnt_disk',
          'chi',
          'sum_no_cert',
          'pre_enter',
          'kiosk_disc',
          'terminal_ans',
          'certificate_number',
          'vat',
      ];
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
      return [
      ];
    }

    /**
     * Lists all VisitorLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->can('VisitorLogView')) {
          throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
          return false;
        }

        $searchModel = new VisitorLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
  
        $canCreate = Yii::$app->user->can('VisitorLogCreate');
        $actions = "";
        //$actions.= Yii::$app->user->can('VisitorLogUpdate')?"{update}":"";
        $afterTable='';
        $columns = include(__DIR__.'/../views/admin/_columns.php');
        if(Yii::$app->user->isGuest){
          $sel_column=Yii::$app->session->get("columns_VisitorLog",false);
        }else{
          $user=Yii::$app->getUser()->getIdentity();
          $sel_column=$user->getActiveColumn("columns_VisitorLog");
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
            'title'=>Yii::t('app', 'VisitorLog list'),
        ]);
    }


   /**
   * Config column in VisitorLog model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionColumns()
  {
    if (Yii::$app->user->isGuest || !Yii::$app->user->can('VisitorLogView')) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }
    $model = new VisitorLog();
    $searchModel = new VisitorLogSearch();

    $request = Yii::$app->request;

    if(!$request->isAjax){
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }

    Yii::$app->response->format = Response::FORMAT_JSON;

    if($request->post('column')){
      $col=$request->post('column');

      if(Yii::$app->user->isGuest){
        Yii::$app->session->set("columns_VisitorLog",$col);
      }else{
        $user=Yii::$app->getUser()->getIdentity();
        $user->setActiveColumn("columns_VisitorLog",$col);
      }

      return [
        'forceReload'=>'#crud-datatable-pjax',
       'content'=>Yii::$app->view->closeModal(),
      ];
    }
    $actions="";
    $columns = include(__DIR__.'/../views/admin/_columns.php');
    if(Yii::$app->user->isGuest){
      $sel_column=Yii::$app->session->get("columns_VisitorLog",false);
    }else{
      $user=Yii::$app->getUser()->getIdentity();
      $sel_column=$user->getActiveColumn("columns_VisitorLog");
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
      'title'=> "Yii::t('app', 'Change visible columns in <?= VisitorLog ?> table')",
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
     * Creates a new VisitorLog model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
      if (Yii::$app->user->isGuest || !Yii::$app->user->can('VisitorLogCreate')) {
        throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
        return false;
      }
      $request = Yii::$app->request;
      $model = new VisitorLog();

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
          'title'=> "Yii::t('app', 'Create new VisitorLog')",
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
          'title'=> "Yii::t('app', 'Create new VisitorLog')",
          'content'=>'<span class="text-success">Create VisitorLog success</span>',
          'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

        ];
      }else{
        return [
          'title'=> "Yii::t('app', 'Create new VisitorLog')",
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
     * Updates an existing VisitorLog model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
      if (Yii::$app->user->isGuest || !Yii::$app->user->can('VisitorLogUpdate')) {
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
        $title=Yii::t('app', 'Update Visitor Log: ' . $model->id, [
    'nameAttribute' => '' . $model->id,
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
     * Finds the VisitorLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VisitorLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VisitorLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Page does not exist'));
        }
    }
}
