<?php

namespace frontend\modules\users\controllers;

use frontend\modules\users\models\Users;
use frontend\modules\users\models\UsersSearch;
use johnitvn\ajaxcrud\BulkButtonWidget;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * AdminController implements the CRUD actions for Users model.
 */
class AdminController extends Controller
{

  private $def_sel_column = [
      'id',
      'name',
      'role',
      'state',
      'cafe',
      'email',
      'color',
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
   * Lists all Users models.
   * @return mixed
   */
  public function actionIndex()
  {
    if (Yii::$app->user->isGuest || !Yii::$app->user->can('UsersView')) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }

    $searchModel = new UsersSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $canCreate = Yii::$app->user->can('UsersCreate');
    $actions = "";
    $actions .= Yii::$app->user->can('UsersDelete') ? "{blocked}" : "";
    $actions .= Yii::$app->user->can('UsersUpdate') ? "{update}" : "";
    $afterTable = '';
    if (Yii::$app->user->can('UsersDelete')) {
      $afterTable = BulkButtonWidget::widget([
              'buttons' =>
                  Html::a('<i class="fa fa-stop"></i>&nbsp; ' . Yii::t('app', 'Blocked All'),
                      ["bulk-blocked"],
                      [
                          "class" => "btn btn-warning btn-xs",
                          'role' => 'modal-remote-bulk',
                          'data-confirm' => false, 'data-method' => false,// for overide yii data api
                          'data-request-method' => 'post',
                          'data-confirm-title' => Yii::t('app', 'Are you sure?'),
                          'data-confirm-message' => Yii::t('app', 'Are you sure want to Blocked this users')]),
          ])
          . Html::a('<i class="fa fa-play"></i>&nbsp; ' . Yii::t('app', 'Restore All'),
              ["bulk-restore"],
              [
                  "class" => "btn btn-science-blue btn-xs",
                  'role' => 'modal-remote-bulk',
                  'data-confirm' => false, 'data-method' => false,// for overide yii data api
                  'data-request-method' => 'post',
                  'data-confirm-title' => Yii::t('app', 'Are you sure?'),
                  'data-confirm-message' => Yii::t('app', 'Are you sure want to restore this users')
              ])
          . '<div class="clearfix"></div>';
    };

    $columns = include(__DIR__ . '/../views/admin/_columns.php');
    if (Yii::$app->user->isGuest) {
      $sel_column = Yii::$app->session->get("columns_Users", false);
    } else {
      $user = Yii::$app->getUser()->getIdentity();
      $sel_column = $user->getActiveColumn("columns_Users");
    }
    if (!$sel_column) {
      $sel_column = $this->def_sel_column;
    }
    foreach ($columns as $k => $column) {
      $column_name = !is_array($column) ? $column : (isset($column['attribute']) ? $column['attribute'] : false);
      $hasPrev = !in_array($column_name, ['franchisee']) || Yii::$app->user->can('AllFranchisee');

      if ($hasPrev && $column_name && !in_array($column_name, $sel_column)) {
        unset($columns[$k]);
      }
    }

    return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'columns' => $columns,
        'canCreate' => $canCreate,
        'afterTable' => $afterTable,
        'title' => "Users",
        'forAllCafe' => true,
    ]);
  }


  /**
   * Config column in Users model.
   * @param integer $id
   * @return mixed
   * @throws NotFoundHttpException if the model cannot be found
   */
  public function actionColumns()
  {
    if (Yii::$app->user->isGuest || !Yii::$app->user->can('UsersView')) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }
    $model = new Users();
    $request = Yii::$app->request;

    if (!$request->isAjax) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }

    Yii::$app->response->format = Response::FORMAT_JSON;

    if ($request->post('column')) {
      $col = $request->post('column');

      if (Yii::$app->user->isGuest) {
        Yii::$app->session->set("columns_Users", $col);
      } else {
        $user = Yii::$app->getUser()->getIdentity();
        $user->setActiveColumn("columns_Users", $col);
      }

      return [
          'forceReload' => '#crud-datatable-pjax',
          'content' => "<script>$('.modal-header .close').click()</script>"
      ];
    }

    $actions = "";
    $columns = include(__DIR__ . '/../views/admin/_columns.php');
    if (Yii::$app->user->isGuest) {
      $sel_column = Yii::$app->session->get("columns_Users", false);
    } else {
      $user = Yii::$app->getUser()->getIdentity();
      $sel_column = $user->getActiveColumn("columns_Users");
    }
    if (!$sel_column) {
      $sel_column = $this->def_sel_column;
    }
    foreach ($columns as $k => $column) {
      $column_name = !is_array($column) ? $column : (isset($column['attribute']) ? $column['attribute'] : false);
      $hasPrev = !in_array($column_name, ['franchisee']) || Yii::$app->user->can('AllFranchisee');

      if (!$column_name || !$hasPrev) {
        unset($columns[$k]);
      } else {
        $columns[$k] = $column_name;
      }
    }

    return [
        'title' => "Change visible columns in Users table",
        'content' => $this->renderAjax('columns', [
            'sel_column' => $sel_column,
            'columns' => $columns,
            'model' => $model,
            'isAjax' => true
        ]),
        'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
            Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

    ];
  }

  /**
   * Creates a new Users model.
   * For ajax request will return json object
   * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
  {
    if (Yii::$app->user->isGuest || !Yii::$app->user->can('UsersCreate')) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }
    $request = Yii::$app->request;
    $model = new Users();

    if (Yii::$app->user->can('CanChangeCafe')) {
      $cafe = Users::getCafesList();
    } else {
      $cafe = [];
    }

    if (!$request->isAjax) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }

    if ($request->isAjax) {
      /*
      *   Process for ajax request
      */
      Yii::$app->response->format = Response::FORMAT_JSON;
      if ($request->isGet) {
        return [
            'title' => "Create new Users",
            'content' => $this->renderAjax('create', [
                'model' => $model,
                'cafes' => $cafe,
                'isAjax' => true
            ]),
            'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
        ];
      } else if ($model->load($request->post()) && $model->save()) {
        return [
            'forceReload' => '#crud-datatable-pjax',
            'title' => "Create new Users",
            'content' => '<span class="text-success">Create Users success</span>',
            'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::a('Create More', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

        ];
      } else {
        return [
            'title' => "Create new Users",
            'content' => $this->renderAjax('create', [
                'model' => $model,
                'cafes' => $cafe,
                'isAjax' => true,
            ]),
            'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])

        ];
      }
    } else {
      /*
      *   Process for non-ajax request
      */
      if ($model->load($request->post()) && $model->save()) {
        return $this->redirect(['index']);
      } else {
        return $this->render('create', [
            'model' => $model,
        ]);
      }
    }

  }

  /**
   * Updates an existing Users model.
   * For ajax request will return json object
   * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id
   * @return mixed
   */
  public function actionUpdate($id)
  {
    if (Yii::$app->user->isGuest || !Yii::$app->user->can('UsersUpdate')) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }

    $request = Yii::$app->request;
    $model = $this->findModel($id);

    if (Yii::$app->user->can('CanChangeCafe')) {
      $cafe = Users::getCafesList();
    } else {
      $cafe = [];
    }

    if (!$request->isAjax) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }

    if ($request->isAjax) {
      /*
      *   Process for ajax request
      */
      $title = Yii::t('app', 'Update Users: {nameAttribute}', [
          'nameAttribute' => '' . $model->name,
      ]);
      Yii::$app->response->format = Response::FORMAT_JSON;
      if ($request->isGet) {
        return [
            'title' => $title,
            'content' => $this->renderAjax('update', [
                'model' => $model,
                'cafes' => $cafe,
                'isAjax' => true,
            ]),
            'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
        ];
      } else if ($model->load($request->post()) && $model->save()) {
        return [
            'forceReload' => '#crud-datatable-pjax',
            'title' => $title,
            'content' => "<script>$('.modal-header .close').click()</script>",
            'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::a('Edit', ['update', 'id' => $id], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])
        ];
      } else {
        return [
            'title' => $title,
            'content' => $this->renderAjax('update', [
                'model' => $model,
                'cafes' => $cafe,
                'isAjax' => true,
            ]),
            'footer' => Html::button('Close', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                Html::button('Save', ['class' => 'btn btn-primary', 'type' => "submit"])
        ];
      }
    } else {
      /*
      *   Process for non-ajax request
      */
      if ($model->load($request->post()) && $model->save()) {
        return $this->redirect(['index']);
      } else {
        return $this->render('update', [
            'model' => $model,
        ]);
      }
    }
  }

  /**
   * Delete an existing Users model.
   * For ajax request will return json object
   * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
   * @param integer $id
   * @return mixed
   */
  public function actionBlocked($id)
  {
    if (Yii::$app->user->isGuest || !Yii::$app->user->can('UsersDelete')) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }
    $request = Yii::$app->request;
    $user = $this->findModel($id);
    $user->state = 1;
    $user->save();

    if ($request->isAjax) {
      /*
      *   Process for ajax request
      */
      Yii::$app->response->format = Response::FORMAT_JSON;
      return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
    } else {
      /*
      *   Process for non-ajax request
      */
      return $this->redirect(['index']);
    }
  }

  public function actionRestore($id)
  {
    if (Yii::$app->user->isGuest || !Yii::$app->user->can('UsersDelete')) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }
    $request = Yii::$app->request;
    $user = $this->findModel($id);
    $user->state = 0;
    $user->save();

    if ($request->isAjax) {
      /*
      *   Process for ajax request
      */
      Yii::$app->response->format = Response::FORMAT_JSON;
      return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
    } else {
      /*
      *   Process for non-ajax request
      */
      return $this->redirect(['index']);
    }
  }

  /**
   * Delete multiple existing Users model.
   * For ajax request will return json object
   * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
   * @param integer $id
   * @return mixed
   */
  public function actionBulkBlocked()
  {
    if (Yii::$app->user->isGuest || !Yii::$app->user->can('UsersDelete')) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }
    $request = Yii::$app->request;
    $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
    foreach ($pks as $pk) {
      $model = $this->findModel($pk);
      $model->state = 1;
      $model->save();
    }

    if ($request->isAjax) {
      /*
      *   Process for ajax request
      */
      Yii::$app->response->format = Response::FORMAT_JSON;
      return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
    } else {
      /*
      *   Process for non-ajax request
      */
      return $this->redirect(['index']);
    }

  }

  public function actionBulkRestore()
  {
    if (Yii::$app->user->isGuest || !Yii::$app->user->can('UsersDelete')) {
      throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Page does not exist'));
      return false;
    }
    $request = Yii::$app->request;
    $pks = explode(',', $request->post('pks')); // Array or selected records primary keys
    foreach ($pks as $pk) {
      $model = $this->findModel($pk);
      $model->state = 0;
      $model->save();
    }

    if ($request->isAjax) {
      /*
      *   Process for ajax request
      */
      Yii::$app->response->format = Response::FORMAT_JSON;
      return ['forceClose' => true, 'forceReload' => '#crud-datatable-pjax'];
    } else {
      /*
      *   Process for non-ajax request
      */
      return $this->redirect(['index']);
    }

  }

  /**
   * Finds the Users model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return Users the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
  {
    if (($model = Users::findOne($id)) !== null) {
      return $model;
    } else {
      throw new NotFoundHttpException(Yii::t('app', 'Page does not exist'));
    }
  }
}
