<?php
return [
    'components' => [
        'assetManager' => [
            //forceCopy makes rendering slow!
            //'forceCopy' => true,
            'bundles' => [
                'wbraganca\dynamicform\DynamicFormAsset' => [
                    'sourcePath' => '@common/eb/assets/js/yii2-dynamic-form', // do not publish the bundle
                    'js' => [
                        'yii2-dynamic-form-custom.js',
                    ]
                ],
            ],
        ],
    ]
];