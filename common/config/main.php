<?php
$twigFunction = require(dirname(dirname(__DIR__)) . '/common/components/twigFunctionList.php');
$twigFunction['translate'] = '\Yii::t';

$config = [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'viewPath' => '@common/mail',
            'htmlLayout' => 'layouts/html',
            'textLayout' => 'layouts/text',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
          //'cache' => 'yii\caching\FileCache',
        ],
        'TwigString' => [
            'class' => 'common\components\TwigString',
            'params' => [
                'cachePath' => '@runtime/Twig/cache',
                'functions' => $twigFunction,
            ],
        ],
        'view' => [
            'class' => 'yii\web\View',
            'defaultExtension' => 'twig',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                  // Array of twig options:
                    'options' => YII_DEBUG ? [
                        'debug' => true,
                        'auto_reload' => true,
                    ] : [
                        'auto_reload' => true,
                    ],
                    'globals' => [
                        'html' => '\yii\helpers\Html',
                        'url' => 'yii\helpers\Url',
                        'ActiveForm' => 'yii\bootstrap\ActiveForm',
                        'MultipleInput' => 'unclead\multipleinput\MultipleInput',
                        'MaskedInput' => 'yii\widgets\MaskedInput',
                    ],
                    'functions' => $twigFunction,
                    'uses' => ['yii\bootstrap'],
                    'extensions' => YII_DEBUG ? [
                        '\Twig_Extension_Debug',
                    ] : [
                    ]
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/language',
                    'on missingTranslation' => ['common\components\TranslationEventHandler', 'handleMissingTranslation']
                ],
                'app' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/language',
                    'on missingTranslation' => ['common\components\TranslationEventHandler', 'handleMissingTranslation']
                ],
                'main' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/language',
                    'on missingTranslation' => ['common\components\TranslationEventHandler', 'handleMissingTranslation']
                ],
                'kvbase' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/language',
                    'on missingTranslation' => ['common\components\TranslationEventHandler', 'handleMissingTranslation']
                ],
            ],
        ],
    ],
];

if (YII_DEBUG) {
  // configuration adjustments for 'dev' environment
  $config['bootstrap'][] = 'debug';
  $config['modules']['debug'] = [
      'class' => 'yii\debug\Module',
      'allowedIPs' => ['*']
  ];
  $config['bootstrap'][] = 'gii';
  $config['modules']['gii'] = [
      'class' => 'yii\gii\Module',
  ];

  //Add kint
  $config['bootstrap'][] = 'kint';
  $config['modules']['kint'] = [
      'class' => 'digitv\kint\Module',
  ];
}

return $config;