<?php

namespace edvlerblog\accounting\assets\toastr;

use yii\web\AssetBundle;

class Toastr extends AssetBundle {

    public $sourcePath = '@bower/toastr';
    public $css = [
        'toastr.css',
    ];
    public $js = [
        'toastr.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];

}
