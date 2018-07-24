<?php
$config= [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/language',
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