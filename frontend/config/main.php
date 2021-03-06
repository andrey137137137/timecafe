<?php
$params = array_merge(
  require __DIR__ . '/../../common/config/params.php',
  require __DIR__ . '/../../common/config/params-local.php',
  require __DIR__ . '/params.php',
  require __DIR__ . '/params-local.php'
);

$config = [
  'id' => 'app-frontend',
  'basePath' => dirname(__DIR__),
  'bootstrap' => [
    'log',
    'cafe',
  ],
  'controllerNamespace' => 'frontend\controllers',
  'language' => defined('LANGUAGE') ? LANGUAGE : 'en-EN',
  'sourceLanguage' => 'dev',
  'components' => [
    'authManager' => [
      //'class' => 'yii\rbac\DbManager',
      'class' => 'frontend\components\RBACmng',
    ],
    'assetManager' => [
      'bundles' => [
        'yii\bootstrap\BootstrapAsset' => [
          'sourcePath' => null,   // не опубликовывать комплект
          'js' => [],
          'css' => [],
        ],
        'yii\web\JqueryAsset' => [
          'sourcePath' => null,   // не опубликовывать комплект
          'js' => [
            '//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js',
          ]
        ],
        'bootstrap.js' => false,
        'bootstrap.css' => false,
      ]
    ],
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
    'view' => [
      'class' => 'frontend\components\ViewBASE',
      'renderers' => [
        'twig' => [
          'globals' => [
            'AppAsset' => 'frontend\assets\AppAsset',
          ]
        ]
      ]
    ],
    'urlManager' => [
      'class' => 'yii\web\UrlManager',
      // Hide index.php
      'showScriptName' => false,
      // Use pretty URLs
      'enablePrettyUrl' => true,
      'rules' => [
        'site/<action>' => '404',
        '<models>/default/<action>' => '404',
        [
          'class' => 'frontend\components\LoginPage',
        ],
        '<module:\w+>/<controller:\w+>/<action:(\w|-)+>' => '<module>/<controller>/<action>',
        '<module:\w+>/<controller:\w+>/<action:(\w|-)+>/<id:\d+>' => '<module>/<controller>/<action>',
        '<models>/admin'=>'/<models>/admin/index',
        '<alias:\w+|change-cafe>' => 'site/<alias>',
        '<models:visits|visitor>/<alias:\w+>'=>'/<models>/default/<alias>',
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
    'cafe'=>[
       'class' => 'frontend\modules\cafe\components\Cafe',
    ],
    'helper'=>[
       'class' => 'common\components\Helper',
    ],
  ],
  'modules' => [
    'users' => [
      'class' => 'frontend\modules\users\Module',
    ],
    'gridview' => [
      'class' => '\kartik\grid\Module'
    ],
    'permit' => [
      'class' => 'developeruz\db_rbac\Yii2DbRbac',
      'params' => [
        'userClass' => 'frontend\modules\users\models\Users',
        'accessRoles' => ['root']
      ]
    ],
    'cafe' => [
      'class' => 'frontend\modules\cafe\Module',
    ],
    'franchisee' => [
      'class' => 'frontend\modules\franchisee\Module',
    ],
    'tarifs' => [
      'class' => 'frontend\modules\tarifs\Module',
    ],
    'visitor' => [
        'class' => 'frontend\modules\visitor\Module',
    ],
    'visits' => [
      'class' => 'frontend\modules\visits\Module',
    ],
    'language' => [
      'class' => 'frontend\modules\language\Module',
    ],
    'permit' => [
        'class' => 'developeruz\db_rbac\Yii2DbRbac',
        //'layout' => '//admin'
        'params' => [
            'userClass' => 'frontend\modules\users\models\Users',
            'accessRoles' => ['root']
        ]
    ],
  ],
  'params' => $params,
];

if (YII_DEBUG) {
  if (!isset($config['modules']['gii']['generators'])) {
    $config['modules']['gii']['generators'] = [];
  }

  $config['modules']['gii']['generators']['ajaxcrud'] = [
    'class' => 'frontend\myTemplates\crud\Generator', // generator class
    'templates' => [ //setting for out templates
      'default' => '@frontend/myTemplates/crud/default', // template name => path to template
    ]
  ];

}

return $config;