<?php
return [
    'adminEmail' => 'admin@example.com',
    'colorPluginOptions' =>  [
        'showPalette' => true,
        'showPaletteOnly' => true,
        'showSelectionPalette' => true,
        'showAlpha' => false,
        'allowEmpty' => false,
        'preferredFormat' => 'name',
        'palette' => [
            [
                "white", "black", "grey", "silver", "gold", "brown",
            ],
            [
                "red", "orange", "yellow", "indigo", "maroon", "pink"
            ],
            [
                "blue", "green", "violet", "cyan", "magenta", "purple",
            ],
        ]
    ],
    'defaultAccess'=>[
        "VisitorView","ChooseCafe"
    ],
    'iCan'=>[
        "startVisit" => true,     //Работа с визитами
        "AnonymousVisitor"=>true, //Анонимный пользователь
        "payCash" => true,        //Оплата наличкой
        "payCard" => true,        //Оплата картой
        "payNOT" => true,         //Отказ оплаты
    ],
    'datetime_option'=>[
        'convertFormat'=>false,
        'presetDropdown' => true,
        'pluginOptions'=>[
            'separator' => ' - ',
            'format' => 'YYYY-MM-DD',
            'locale' => [
                'format' => 'YYYY-MM-DD'
            ],
        ]
    ]
];
