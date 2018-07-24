<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
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
        ]
    ],
    'params' => $params,
];
