<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

$config=  [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'language' => defined('LANGUAGE') ? LANGUAGE : 'en-EN',
    'sourceLanguage' => 'dev',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'frontend\modules\users\models\Users',
            'enableAutoLogin' => true,
          /*'on afterLogin' => function ($event) {
            frontend\modules\users\models\Users::afterLogin($event->identity->id);
          },*/
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
          // Hide index.php
            'showScriptName' => false,
          // Use pretty URLs
            'enablePrettyUrl' => true,
            'rules' => [
                'site/<action>' => '404',
                [ // обработка локализации сайта
                    'class' => 'frontend\components\LoginPage',
                ],
                '<alias:\w+>' => 'site/<alias>',
            ],
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
            ],
        ],
        'session' => [
          // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'modules' => [
        'users' => [
            'class' => 'frontend\modules\users\Module',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'permit' => [
          'class' => 'developeruz\db_rbac\Yii2DbRbac',
          'params' => [
            'userClass' => 'frontend\modules\users\models\Users',
            'accessRoles' => ['admin']
          ]
        ],
    ],
    'params' => $params,
];

if (YII_DEBUG) {
  if(!isset($config['modules']['gii']['generators'])){
    $config['modules']['gii']['generators']=[];
  }

  $config['modules']['gii']['generators']['ajaxcrud']=[
      'class' => 'frontend\myTemplates\crud\Generator', // generator class
      'templates' => [ //setting for out templates
          'default' => '@frontend/myTemplates/crud/default', // template name => path to template
      ]
  ];

}

return $config;